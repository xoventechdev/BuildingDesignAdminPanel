
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
                $fontDesigns = 'uploads/font_designs/' . $fontFilename;

                if (!move_uploaded_file($fontDesign['tmp_name'], $fontDesignPath)) {
                    throw new Exception("Failed to upload font design.");
                }
            } elseif ($fontDesign['error'] !== UPLOAD_ERR_NO_FILE) {
                throw new Exception("Error in font design upload: " . $fontDesign['error']);
            }
    
            // Handle floorDesign files
            $floorDesignFiles = $_FILES['floor_design'];
            $floorUploadDir = __DIR__ . '/../uploads/floor_designs/';
            $uploadedFiles = [];
    
            for ($i = 0; $i < count($floorDesignFiles['name']); $i++) {
                if ($floorDesignFiles['error'][$i] === UPLOAD_ERR_OK) {
                    $filename = uniqid() . '_' . basename($floorDesignFiles['name'][$i]);
                    $filePath = $floorUploadDir . $filename;
    
                    if (move_uploaded_file($floorDesignFiles['tmp_name'][$i], $filePath)) {
                        $uploadedFiles[] = 'uploads/floor_designs/' . $filename;
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
    } else {
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




exit();

?>
