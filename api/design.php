
<?php
include "./../config.php";
header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST["key"] === "itemAdd") {
        try {
            if (!isset($_POST['title'], $_POST['bedroom'], $_POST['floor'], $_POST['status'], $_POST['info'])) {
                throw new Exception("All required fields must be provided.");
            }
            $title = $_POST['title'];
            $bedroom = $_POST['bedroom'];
            $floor = $_POST['floor'];
            $status = $_POST['status'];
            $info = $_POST['info'];
    
            // Handle fontDesign upload
            $fontDesign = $_FILES['fontDesign'];
            $uploadDir = __DIR__ . '/../uploads/font_designs/';
            $fontDesigns = null;
    

            if ($fontDesign['error'] === UPLOAD_ERR_OK) {
                $fontFilename = uniqid() . '_' . basename($fontDesign['name']);
                $fontDesignPath = $uploadDir . $fontFilename;
                $compressedPath = $uploadDir . 'compressed_' . $fontFilename;
                $fontDesigns = 'uploads/font_designs/compressed_' . $fontFilename;
            
                if (!move_uploaded_file($fontDesign['tmp_name'], $fontDesignPath)) {
                    throw new Exception("Failed to upload font design.");
                }
            
                // Compress the uploaded image
                compressImage($fontDesignPath, $compressedPath, 50); // Adjust quality (0-100)
                unlink($fontDesignPath); // Remove original file after compression
            }

    
            // Handle floorDesign files
            $floorDesignFiles = $_FILES['floor_design'];
            $floorUploadDir = __DIR__ . '/../uploads/floor_designs/';
            $uploadedFiles = [];
    
            // for ($i = 0; $i < count($floorDesignFiles['name']); $i++) {
            //     if ($floorDesignFiles['error'][$i] === UPLOAD_ERR_OK) {
            //         $filename = uniqid() . '_' . basename($floorDesignFiles['name'][$i]);
            //         $filePath = $floorUploadDir . $filename;
    
            //         if (move_uploaded_file($floorDesignFiles['tmp_name'][$i], $filePath)) {
            //             $uploadedFiles[] = 'uploads/floor_designs/' . $filename;
            //         }
            //     }
            // }

            for ($i = 0; $i < count($floorDesignFiles['name']); $i++) {
                if ($floorDesignFiles['error'][$i] === UPLOAD_ERR_OK) {
                    $filename = uniqid() . '_' . basename($floorDesignFiles['name'][$i]);
                    $filePath = $floorUploadDir . $filename;
                    $compressedPath = $floorUploadDir . 'compressed_' . $filename;
            
                    if (move_uploaded_file($floorDesignFiles['tmp_name'][$i], $filePath)) {
                        // Compress the uploaded image
                        compressImage($filePath, $compressedPath, 75); // Adjust quality (0-100)
                        unlink($filePath); // Remove original file after compression
                        $uploadedFiles[] = 'uploads/floor_designs/compressed_' . $filename;
                    }
                }
            }
            
    
            // Insert into database
            $stmt = $pdo->prepare("INSERT INTO buildingItem (title, bedRoom, floorNumber, status, basicInfo, fontDesign) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $bedroom, $floor, $status, $info, $fontDesigns]);
            $buildingId = $pdo->lastInsertId();
    
            foreach ($uploadedFiles as $file) {
                $stmt = $pdo->prepare("INSERT INTO floordesign (buildingId , url, comment) VALUES (?, ?, 'floor')");
                $stmt->execute([$buildingId, $file]);
            }

            echo json_encode([
                "success" => true,
                "message" => "Building and designs added successfully!",
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                "success" => true,
                "message" => $e->getMessage()
            ]);
        }
    } else  if ($_POST["key"] === "itemUpdate") {
        try {
            $id = $_POST['id'];
            $title = $_POST['title'];
            $bedroom = $_POST['bedroom'];
            $floor = $_POST['floor'];
            $status = $_POST['status'];
            $info = $_POST['info'];

            $fontDesign = $_FILES['fontDesign'];
            $floorDesigns = $_FILES['floor_design'];
            $existingFloorDesigns = $_POST['existing_floor_designs'] ?? [];

            // Update font design if uploaded
            if ($fontDesign['error'] === UPLOAD_ERR_OK) {
                $fontPath = 'uploads/font_designs/' . $fontDesign['name'];
                $fontUploadDirU = __DIR__ . '/../uploads/font_designs/' . $fontDesign['name'];
                move_uploaded_file($fontDesign['tmp_name'], $fontUploadDirU);
                $stmt = $pdo->prepare("UPDATE buildingItem SET title = ?, bedRoom = ?, floorNumber = ?, status = ?, basicInfo = ?, fontDesign = ? WHERE id = ?");
                $stmt->execute([$title, $bedroom, $floor, $status, $info, $fontPath, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE buildingItem SET title = ?, bedRoom = ?, floorNumber = ?, status = ?, basicInfo = ? WHERE id = ?");
                $stmt->execute([$title, $bedroom, $floor, $status, $info, $id]);
            }

            // Fetch current floor designs from database
            $stmt = $pdo->prepare("SELECT id FROM floorDesign WHERE buildingId = ?");
            $stmt->execute([$id]);
            $currentDesignIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // Identify designs to delete
            $designsToDelete = array_diff($currentDesignIds, $existingFloorDesigns);
            foreach ($designsToDelete as $designId) {
                $stmt = $pdo->prepare("DELETE FROM floorDesign WHERE id = ?");
                $stmt->execute([$designId]);
            }

            // Upload new floor designs
            foreach ($floorDesigns['tmp_name'] as $index => $tmpName) {
                if (!empty($tmpName) && $floorDesigns['error'][$index] === UPLOAD_ERR_OK) {
                    $fileName = basename($floorDesigns['name'][$index]);
                    $floorPath = 'uploads/floor_designs/' . $fileName;
                    $floorUploadDirU = __DIR__ . '/../uploads/floor_designs/' . $fileName;

                    if (move_uploaded_file($tmpName, $floorUploadDirU)) {
                        $stmt = $pdo->prepare("INSERT INTO floorDesign (buildingId, url, comment) VALUES (?, ?, 'floor')");
                        $stmt->execute([$id, $floorPath]);
                    }
                }
            }

            echo json_encode([
                "success" => true,
                "message" => "Building and designs updated successfully!",
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "success" => true,
                "message" => $e->getMessage()
            ]);
        }
    } else if ($_POST["key"] === "AdminLogIn") {
        try {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $stmt = $pdo->prepare("SELECT * FROM usertable WHERE email = ? AND userRole = 'admin'");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && md5($password) === $user['password']) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_name'] = $user['name'];
                $_SESSION['id'] = $user['id'];
    
                echo json_encode([
                    "success" => true,
                    "message" => "Admin logged in successfully"
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Invalid email or password."
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "success" => true,
                "message" => $e->getMessage()
            ]);
        }
    } else if ($_POST["key"] === "adsControl") {
        try {
            $banner = $_POST["banner"];
            $interstitial = $_POST["interstitial"];
            $rewarded = $_POST["rewarded"];
            $appOpen = $_POST["appOpen"];
            $apiKey = $_POST["apiKey"];

            $stmt = $pdo->prepare(
                "UPDATE adscontrol SET banner = ?, interstitial = ?, rewarded = ?, appOpen = ?, apiKey= ?"
            );
            $stmt->execute([$banner, $interstitial, $rewarded, $appOpen, $apiKey]);

            echo json_encode([
                "success" => true,
                "message" => "Ads updated successfully."
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    } else if ($_POST["key"] === "userAdd") {
        try {
            $name = $_POST["name"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $mobile = $_POST["mobile"];
            $gender = $_POST["gender"];
            $address = $_POST["address"];
            $country = $_POST["country"];
            $userRole = $_POST["userRole"];

            $stmt = $pdo->prepare("INSERT INTO usertable (name , email, password , mobile, gender , address, country, userRole) VALUES (?,?,?,?,?,?,?,?)");
            $stmt->execute([$name, $email, md5($password), $mobile, $gender, $address, $country, $userRole]);

            echo json_encode([
                "success" => true,
                "message" => "User added successfully."
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    } else if ($_POST["key"] === "userEdit") {
        try {
            $id = $_POST["id"];
            $name = trim($_POST["name"]);
            $email = trim($_POST["email"]);
            $password = trim($_POST["password"]);
            $mobile = trim($_POST["mobile"]);
            $gender = $_POST["gender"];
            $address = trim($_POST["address"]);
            $country = trim($_POST["country"]);
            $userRole = $_POST["userRole"];
        
            // Validate inputs
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode([
                    "success" => false,
                    "message" => "Invalid email address."
                ]);
                return;
            }
        
            if (!in_array($gender, ['male', 'female', 'other'])) {
                echo json_encode([
                    "success" => false,
                    "message" => "Invalid gender value."
                ]);
                return;
            }
        
            if (!in_array($userRole, ['normal', 'admin'])) {
                echo json_encode([
                    "success" => false,
                    "message" => "Invalid user role value."
                ]);
                return;
            }
        
            // Check if email already exists for a different user
            $stmt = $pdo->prepare("SELECT * FROM usertable WHERE email = ? AND id != ?");
            $stmt->execute([$email, $id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
            if ($user) {
                echo json_encode([
                    "success" => false,
                    "message" => "This email address is already in use by another user."
                ]);
                return;
            }
        
            // Prepare and execute the appropriate query
            if (empty($password)) {
                $stmt = $pdo->prepare("UPDATE usertable SET name = ?, email = ?, mobile = ?, gender = ?, address = ?, country = ?, userRole = ? WHERE id = ?");
                $stmt->execute([$name, $email, $mobile, $gender, $address, $country, $userRole, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE usertable SET name = ?, email = ?, password = ?, mobile = ?, gender = ?, address = ?, country = ?, userRole = ? WHERE id = ?");
                $stmt->execute([$name, $email, md5($password), $mobile, $gender, $address, $country, $userRole, $id]);
            }
        
            echo json_encode([
                "success" => true,
                "message" => "User updated successfully."
            ]);
        } catch (Exception $e) {
            // Log the actual error message to a file or monitoring system (not shown to the user)
            error_log($e->getMessage());
        
            echo json_encode([
                "success" => false,
                "message" => "An error occurred while updating the user. Please try again later."
            ]);
        }
    }  else {
        echo json_encode([
            "success" => false,
            "message" => "Invalid key value.",
        ]);
    }      
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    if ($data["key"] === "itemDelete") {
        $id = $data["id"];
        try {
            $pdo->beginTransaction();

            // Fetch associated floor files
            $stmt = $pdo->prepare(
                "SELECT url FROM floordesign WHERE buildingId = ?"
            );
            $stmt->execute([$id]);
            $floorfiles = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch font design file
            $stmt = $pdo->prepare(
                "SELECT fontDesign FROM buildingItem WHERE id = ?"
            );
            $stmt->execute([$id]);
            $fontfile = $stmt->fetch(PDO::FETCH_ASSOC); // Use fetch() for a single result

            // Delete associated floor designs
            $stmt = $pdo->prepare(
                "DELETE FROM floordesign WHERE buildingId = ?"
            );
            $stmt->execute([$id]);

            // Delete the building item
            $stmt = $pdo->prepare("DELETE FROM buildingItem WHERE id = ?");
            $stmt->execute([$id]);

            $pdo->commit();

            // Delete font design file (if it exists)
            if ($fontfile && isset($fontfile["fontDesign"])) {
                $filePath = __DIR__ . '/../' . $fontfile["fontDesign"]; 
                if (file_exists($filePath)) {
                    if (!unlink($filePath)) {
                        error_log(
                            "Failed to delete font design file: $filePath"
                        ); 
                    }
                } else {
                    error_log("Font design file not found: $filePath"); 
                }
            }

            // Delete floor design files
            foreach ($floorfiles as $file) {
                $filePath = __DIR__ . '/../' . $file["url"]; 
                if (file_exists($filePath)) {
                    if (!unlink($filePath)) {
                        error_log(
                            "Failed to delete floor design file: $filePath"
                        ); 
                    }
                } else {
                    error_log("Floor design file not found: $filePath"); 
                }
            }

            echo json_encode([
                "success" => true,
                "message" => 'Item deleted successfully.',
            ]);
        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode([
                "success" => false,
                "message" => "Error: " . $e->getMessage(),
            ]);
        }
    }  else  if ($data["key"] === "userDelete") {
        $id = $data["id"];
        try {
            $stmt = $pdo->prepare("DELETE FROM usertable WHERE id = ?");
            $stmt->execute([$id]);

            echo json_encode([
                "success" => true,
                "message" => 'User deleted successfully.',
            ]);
        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode([
                "success" => false,
                "message" => "Error: " . $e->getMessage(),
            ]);
        }
    }  else {
        echo json_encode([
            "success" => false,
            "message" => "Invalid key value.",
        ]);
    }
    
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if ($_GET["key"] === "itemListLastFive") {
            try {
                $buildings = $pdo->query("SELECT * FROM buildingitem ORDER BY id DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode([
                    "success" => true,
                    "message" => $buildings,
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    "success" => false,
                    "message" => "Error: " . $e->getMessage(),
                ]);
            }
        } if ($_GET["key"] === "itemBasedOnId") {
            $id = $_GET['id'];
            try {
                $stmtBuilding = $pdo->prepare('SELECT * FROM buildingitem WHERE id = :id');
                $stmtBuilding->execute(['id' => $id]);
                $building = $stmtBuilding->fetchAll(PDO::FETCH_ASSOC);
            
                $stmtDesign = $pdo->prepare('SELECT * FROM floordesign WHERE buildingId = :id');
                $stmtDesign->execute(['id' => $id]);
                $design = $stmtDesign->fetchAll(PDO::FETCH_ASSOC);
            
                echo json_encode([
                    "success" => true,
                    "building" => $building,
                    "design" => $design,
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    "success" => false,
                    "message" => "Error: " . $e->getMessage(),
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Invalid key value.",
            ]);
        }
   
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request. Requried ",
    ]);
}





function compressImage($source, $destination, $quality) {
    // Get image info
    $imageInfo = getimagesize($source);
    $mime = $imageInfo['mime'];

    // Create a new image resource based on the file type
    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
            break;
        case 'image/png':
            $image = imagecreatefrompng($source);
            imagealphablending($image, true);
            imagesavealpha($image, true);
            break;
        case 'image/webp':
            $image = imagecreatefromwebp($source);
            break;
        default:
            throw new Exception("Unsupported image type: " . $mime);
    }

    // Save the compressed image
    switch ($mime) {
        case 'image/jpeg':
            imagejpeg($image, $destination, $quality);
            break;
        case 'image/png':
            imagepng($image, $destination, floor($quality / 10)); // Quality for PNG is 0-9
            break;
        case 'image/webp':
            imagewebp($image, $destination, $quality);
            break;
    }

    // Free memory
    imagedestroy($image);
}


exit();

?>
