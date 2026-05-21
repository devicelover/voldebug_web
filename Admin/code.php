
<?php
require_once __DIR__ . '/../includes/csrf.php';
csrf_require();
include 'partials/session.php';
include 'partials/config.php'; 
$default_status = "Active";
if (isset($_POST["addUser"])) 
{
     $name = $_POST["name"];
    //  $phone = $_POST["phone"];
     $email = $_POST["email"];
     $password = $_POST["password"];   
     $confirmpassword = $_POST["confirmpassword"];   
     $hashed_password = password_hash($password, PASSWORD_DEFAULT);
     //    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';

     if ($password == $confirmpassword)
        {
            $check = $con->prepare("SELECT useremail FROM users WHERE useremail = ?");
            $check->bind_param('s', $email);
            $check->execute();
            $res = $check->get_result();

            if ($res && $res->num_rows > 0)
            {
                $_SESSION["Status"] = " email Alrady Exists";
                header("Location: register.php");
            }
            else {
        $stmt = $con->prepare("INSERT INTO users (username, useremail, password) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $name, $email, $hashed_password);
        if ($stmt->execute()) {
            $_SESSION["Status"] = "User addeded Successfully";
            header("Location: register.php");
        } else {
            $_SESSION["Status"] = "User Regristation Fail";
            header("Location: register.php");
        }
        $con->close();
            }

        
        } 
        else {
            $_SESSION["Status"] = "Password And Confirm Password Dosnt match";
            header("Location: register.php");
        }
}


if (isset($_POST["updateUser"]))
{
     $user_id = (int) $_POST["user_id"];
     $name = $_POST["name"];
     $email = $_POST["email"];
     $password = $_POST["password"];
     $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $execute = $con->prepare("UPDATE users SET username = ?, useremail = ?, password = ? WHERE id = ?");
    $execute->bind_param('sssi', $name, $email, $hashed_password, $user_id);

    if ($execute->execute()) {
        $_SESSION["Status"] = "User Uptated Successfully";
        header("Location: register.php");
    } else {
        $_SESSION["Status"] = "User updating Fail";
        header("Location: register.php");
    }
} 


    // Project


if (isset($_POST["add_project"])) {
    // Capture form data
    $name = $_POST["name"];
    $smalldescription = $_POST["smalldescription"];
    $longdescription = $_POST["longdescription"];
    $client_name = $_POST["client_name"];
    $time = $_POST["time"];
    $Finished_time = $_POST["Finished_time"];
    $link = $_POST["link"];
    $status = isset($_POST['toggle']) && !empty($_POST['toggle']) ? $_POST['toggle'] : $default_status;
    $image = $_FILES["image"]["name"];

    // Allowed file extensions
    $allow_extencions = array("jpg", "jpeg", "png", "gif");
    $file_extencions = pathinfo($image, PATHINFO_EXTENSION);

    // Check file extension
    if (!in_array($file_extencions, $allow_extencions)) {
        echo '<script>
                alert("Only image files are allowed.");
                window.location.href = "projects.php";
              </script>';
        exit();
    } else {
        // Create a new filename
        $filename = time() . "." . $file_extencions;

        // SQL query
        $category_query = "INSERT INTO projects (name, short_description, long_description, client_name, time, Finished_time, links, toggle, image) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Check database connection
        if (!$con) {
            echo '<script>
                    alert("Database connection failed: ' . addslashes(mysqli_connect_error()) . '");
                    window.location.href = "projects.php";
                  </script>';
            exit();
        }

        // Prepare and bind
        $stmt = $con->prepare($category_query);
        if (!$stmt) {
            echo '<script>
                    alert("Preparation failed: ' . addslashes($con->error) . '");
                    window.location.href = "projects.php";
                  </script>';
            exit();
        }

        $stmt->bind_param("sssssssss", $name, $smalldescription, $longdescription, $client_name, $time, $Finished_time, $link, $status, $filename);

        // Execute the statement
        if ($stmt->execute()) {
            // Move the uploaded file
            if (move_uploaded_file($_FILES["image"]["tmp_name"], 'images/project_images/' . $filename)) {
                echo '<script>
                        alert("Project inserted successfully.");
                        window.location.href = "projects.php";
                      </script>';
                exit();
            } else {
                echo '<script>
                        alert("Failed to move uploaded file.");
                        window.location.href = "index.php";
                      </script>';
                exit();
            }
        } else {
            echo '<script>
                    alert("Project insertion failed: ' . addslashes($stmt->error) . '");
                    window.location.href = "projects.php";
                  </script>';
            exit();
        }

    }

}

//update project

if (isset($_POST['update_project'])) {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $smalldescription = $_POST['smalldescription'];
    $longdescription = $_POST['longdescription'];
    $client_name = $_POST['client_name'];
    $link = $_POST['link'];
    $time = $_POST['time'];
    $Finished_time = $_POST['Finished_time'];
    $status = $_POST['status'];
    $current_image = $_POST['current_image'];
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $allowed_types = array('jpg', 'jpeg', 'png', 'gif');

    if ($image) {
        $image_extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));

        if (in_array($image_extension, $allowed_types)) {
            $image_folder = 'images/project_images/' . $image;
            move_uploaded_file($image_tmp_name, $image_folder);
        } else {
            echo "Invalid image format. Only JPG, JPEG, PNG, and GIF are allowed.";
            exit;
        }
    } else {
        $image = $current_image;
    }

    $stmt = $con->prepare("UPDATE projects SET name=?, short_description=?, long_description=?, client_name=?, links=?, time=?, Finished_time=?, toggle=?, image=? WHERE id=?");
    $stmt->bind_param('sssssssssi', $name, $smalldescription, $longdescription, $client_name, $link, $time, $Finished_time, $status, $image, $user_id);

    if ($stmt->execute()) {
        header("Location: projects_edit.php");
    } else {
        echo "Failed to update project: " . htmlspecialchars($stmt->error);
    }
}




    // Photo


if (isset($_POST["add_photo"])) {
    // Capture form data
    $image = $_FILES["image"]["name"];

    // Allowed file extensions
    $allowed_extensions = array("jpg", "jpeg", "png", "gif");
    $file_extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));

    // Check file extension
    if (!in_array($file_extension, $allowed_extensions)) {
        echo '<script>
                alert("Only image files (jpg, jpeg, png, gif) are allowed.");
                window.location.href = "photo_gallery.php";
              </script>';
        exit();
    } else {
        // Create a new filename
        $filename = time() . "." . $file_extension;

        // Check database connection
        if (!$con) {
            echo '<script>
                    alert("Database connection failed: ' . addslashes(mysqli_connect_error()) . '");
                    window.location.href = "photo_gallery.php";
                  </script>';
            exit();
        }

        // Prepare SQL query
        $category_query = "INSERT INTO photo_gallery (image) VALUES (?)";

        // Prepare and bind
        $stmt = $con->prepare($category_query);
        if (!$stmt) {
            echo '<script>
                    alert("Preparation failed: ' . addslashes($con->error) . '");
                    window.location.href = "photo_gallery.php";
                  </script>';
            exit();
        }

        $stmt->bind_param("s", $filename);

        // Execute the statement
        if ($stmt->execute()) {
            // Move the uploaded file
            if (move_uploaded_file($_FILES["image"]["tmp_name"], 'images/gallery/' . $filename)) {
                echo '<script>
                        alert("Image inserted successfully.");
                        window.location.href = "photo_gallery.php";
                      </script>';
                exit();
            } else {
                echo '<script>
                        alert("Failed to move uploaded file.");
                        window.location.href = "photo_gallery.php";
                      </script>';
                exit();
            }
        } else {
            echo '<script>
                    alert("Image insertion failed: ' . addslashes($stmt->error) . '");
                    window.location.href = "photo_gallery.php";
                  </script>';
            exit();
        }
    }
}



//update photo 


if (isset($_POST["update_photo"])) {
    $id = $_POST["user_id"];
    $image = $_FILES["image"]["name"];
    $old_image = $_POST['old_image'];

    // If a new image is provided
    if ($image != '') {
        $update_filename = $image;
        $allowed_extensions = array("jpg", "jpeg", "png", "gif");
        $file_extension = strtolower(pathinfo($update_filename, PATHINFO_EXTENSION));
        $filename = time() . "." . $file_extension;

        // Check file extension
        if (!in_array($file_extension, $allowed_extensions)) {
            $_SESSION["Status"] = "Only image files (jpg, jpeg, png, gif) are allowed.";
            header("Location: photo_gallery_edit.php?id=" . $id);
            exit();
        }
    } else {
        $filename = $old_image;
    }

    // Prepare SQL query
    $category_query = "UPDATE photo_gallery SET image = ? WHERE id = ?";
    $stmt = $con->prepare($category_query);
    if (!$stmt) {
        echo '<script>
                alert("Preparation failed: ' . addslashes($con->error) . '");
                window.location.href = "photo_gallery_edit.php?id=' . $id . '";
              </script>';
        exit();
    }
    $stmt->bind_param("si", $filename, $id);

    // Execute the statement
    if ($stmt->execute()) {
        // If a new image is provided, move the uploaded file
        if ($image != '') {
            move_uploaded_file($_FILES["image"]["tmp_name"], 'images/gallery/' . $filename);
        }
        echo '<script>
                alert("Image Updated Successfully.");
                window.location.href = "photo_gallery.php";
              </script>';
    } else {
        echo '<script>
                alert("Image Update Failed: ' . addslashes($stmt->error) . '");
                window.location.href = "photo_gallery_edit.php?id=' . $id . '";
              </script>';
    }

}


// AI School Gallery - Add
if (isset($_POST["add_ai_school_photo"])) {
    $image = $_FILES["image"]["name"];
    $title = trim($_POST["title"] ?? '');
    $allowed_extensions = array("jpg", "jpeg", "png", "gif");
    $file_extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));

    if (!in_array($file_extension, $allowed_extensions)) {
        echo '<script>alert("Only image files (jpg, jpeg, png, gif) are allowed."); window.location.href = "ai_school_gallery.php";</script>';
        exit();
    }
    $filename = time() . "." . $file_extension;
    $target_dir = 'images/ai_school_gallery/';
    if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);

    $stmt = $con->prepare("INSERT INTO ai_school_gallery (image, title) VALUES (?, ?)");
    $stmt->bind_param("ss", $filename, $title);
    if ($stmt->execute() && move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $filename)) {
        echo '<script>alert("AI School photo added successfully."); window.location.href = "ai_school_gallery.php";</script>';
    } else {
        echo '<script>alert("Failed to add photo."); window.location.href = "ai_school_gallery.php";</script>';
    }
    exit();
}

// AI School Gallery - Update
if (isset($_POST["update_ai_school_photo"])) {
    $id = (int) $_POST["user_id"];
    $title = trim($_POST["title"] ?? '');
    $image = $_FILES["image"]["name"];
    $old_image = $_POST['old_image'] ?? '';
    $target_dir = 'images/ai_school_gallery/';

    if ($image != '') {
        $allowed_extensions = array("jpg", "jpeg", "png", "gif");
        $file_extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));
        if (!in_array($file_extension, $allowed_extensions)) {
            echo '<script>alert("Only image files allowed."); window.location.href = "ai_school_gallery_edit.php?id=' . $id . '";</script>';
            exit();
        }
        $filename = time() . "." . $file_extension;
        if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $filename);
    } else {
        $filename = $old_image;
    }

    $stmt = $con->prepare("UPDATE ai_school_gallery SET image = ?, title = ? WHERE id = ?");
    $stmt->bind_param("ssi", $filename, $title, $id);
    if ($stmt->execute()) {
        echo '<script>alert("AI School photo updated successfully."); window.location.href = "ai_school_gallery.php";</script>';
    } else {
        echo '<script>alert("Update failed."); window.location.href = "ai_school_gallery_edit.php?id=' . $id . '";</script>';
    }
    exit();
}


    // Client


if (isset($_POST["add_client"])) {
    // Capture form data
    $client_name = $_POST["client_name"];
    $description = $_POST["description"];
    $project_info = $_POST["project_info"];
    $link = $_POST["link"];
    $email = $_POST["email"];
    $image = $_FILES["images"]["name"];
    $status = isset($_POST['toggle']) && !empty($_POST['toggle']) ? $_POST['toggle'] : $default_status;

    // Allowed file extensions
    $allow_extensions = array("jpg", "jpeg", "png", "gif");
    $file_extensions = pathinfo($image, PATHINFO_EXTENSION);

    // Check file extension
    if (!in_array($file_extensions, $allow_extensions)) {
        echo '<script>
                alert("Only image files are allowed.");
                window.location.href = "client.php";
              </script>';
        exit();
    } else {
        // Create a new filename
        $filename = time() . "." . $file_extensions;

        // SQL query
        $category_query = "INSERT INTO clients (name, description, project_info, email,links, toggle, images) 
                           VALUES ( ?, ?, ?, ?, ?, ?, ?)";

        // Check database connection
        if (!$con) {
            echo '<script>
                    alert("Database connection failed: ' . addslashes(mysqli_connect_error()) . '");
                    window.location.href = "client.php";
                  </script>';
            exit();
        }

        // Prepare and bind
        $stmt = $con->prepare($category_query);
        if (!$stmt) {
            echo '<script>
                    alert("Preparation failed: ' . addslashes($con->error) . '");
                    window.location.href = "client.php";
                  </script>';
            exit();
        }

        $stmt->bind_param("sssssss", $client_name, $description, $project_info,$email, $link, $status, $filename);

        // Execute the statement
        if ($stmt->execute()) {
            // Move the uploaded file
            if (move_uploaded_file($_FILES["images"]["tmp_name"], 'images/client_images/' . $filename)) {
                echo '<script>
                        alert("Client Added successfully.");
                        window.location.href = "client.php";
                      </script>';
                exit();
            } else {
                echo '<script>
                        alert("Failed to move uploaded file.");
                        window.location.href = "client.php";
                      </script>';
                exit();
            }
        } else {
            echo '<script>
                    alert("Client insertion failed: ' . addslashes($stmt->error) . '");
                    window.location.href = "client.php";
                  </script>';
            exit();
        }

    }

}



if (isset($_POST["update_client"])) {
    $id = $_POST["user_id"];
    $name = $_POST["name"];
    $description = $_POST["description"];
    $project_info = $_POST["project_info"];
    $email = $_POST["email"];
    $link = $_POST["link"];
    $image = $_FILES["image"]["name"];
    $old_image = $_POST['old_image'];
    $status = $_POST['status'];

    $update_filename = $old_image;

    if ($image != '') {
        $allow_extensions = array("jpg", "jpeg", "png", "gif");
        $file_extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));
        if (!in_array($file_extension, $allow_extensions)) {
            echo '<script>
            alert("Only image files (jpg, jpeg, png, gif) are allowed.");
            window.location.href = "client_edit1.php";
            </script>';
            exit();
        } else {
            $filename = time() . "." . $file_extension;
            $update_filename = $filename;
            move_uploaded_file($_FILES["image"]["tmp_name"], 'images/client_images/' . $filename);
        }
    }

    $category_query = "UPDATE clients SET name=?, description=?, project_info=?, email=?, links=?, toggle=?, images=? WHERE id=?";
    $stmt = $con->prepare($category_query);
    $stmt->bind_param("sssssssi", $name, $description, $project_info, $email, $link, $status, $update_filename, $id);

    if ($stmt->execute()) {
        echo '<script>
        alert("Client Updated Successfully");
        window.location.href = "client_edit.php";
        </script>';
        exit();
    } else {
        echo '<script>
        alert("Client Update Failed");
        window.location.href = "client_edit.php";
        </script>';
        exit();
    }
}




//Services

$default_status = "Active";
if (isset ($_POST['service'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = $_POST['service_category'];
    // $status = $_POST['toggle']; // Default status
    $status = isset($_POST['toggle']) && !empty($_POST['toggle']) ? $_POST['toggle'] : $default_status;
    $target_dir = "images/service_images/";
    $image = $_FILES["image"]["name"];
    $target_file = $target_dir . basename($image);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
        echo "File is not an image.";
    }

    if (move_uploaded_file($_FILES['image']['tmp_name'],$target_file)) {
        $stmt = $con->prepare("INSERT INTO `services`(name, description, service_category, image, toggle) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssss', $name, $description, $category, $image, $status);
        if ($stmt->execute()) {
            echo "<script>alert('Service added'); window.location.href='services.php'</script>";
        } else {
            echo "<script>alert('Error'); window.location.href='services.php?error=true'</script>";
        }
    }

}


$service = null; // Initialize service variable



if (isset($_POST['delete_service'])) {
    $id = (int) $_POST['id'];
    $stmt = $con->prepare("DELETE FROM `services` WHERE id = ?");
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        header("Location: services.php");
    } else {
        echo "Error: " . htmlspecialchars($stmt->error);
    }
}

// mysqli_close($con);



//Team


$default_status = "Active";
if (isset ($_POST['team'])) {
    $name = $_POST['name'];
    $designation = $_POST['designation'];
    $email = $_POST['email'];
    $facebook = $_POST['facebook'];
    $github = $_POST['github'];
    $linkedin = $_POST['linkedin'];
    $instagram = $_POST['instagram'];
    $status = isset($_POST['toggle']) && !empty($_POST['toggle']) ? $_POST['toggle'] : $default_status;


    $targetDir = "images/team_images/";
    $image = $_FILES["image"]["name"];
    $target_file = $targetDir . basename($image);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $fileName = basename($_FILES["image"]["name"]);
    // $targetFilePath = $targetDir . $fileName;
    // $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
        echo "File is not an image.";
    }

    if (move_uploaded_file($_FILES['image']['tmp_name'],$target_file)) {
        $stmt = $con->prepare("INSERT INTO `team` (name, designation, email, image, facebook, github, linkedin, instagram, toggle) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssssss', $name, $designation, $email, $fileName, $facebook, $github, $linkedin, $instagram, $status);
        if ($stmt->execute()) {
            echo "<script>alert('Team Member added'); window.location.href='team.php'</script>";
        } else {
            echo "<script>alert('Error'); window.location.href='team.php?error=true'</script>";
        }
    }

}

   //Career

   if (isset($_POST["add_career"])) {
    $job_name = $_POST["name"];
    $description = $_POST["description"];
    $qualification_info = $_POST["qualification_info"];
   

        // SQL query
        $category_query = "INSERT INTO career (job_name, job_description, qualification) 
                           VALUES ( ?, ?, ?)";

        // Check database connection
        if (!$con) {
            echo '<script>
                    alert("Database connection failed: ' . addslashes(mysqli_connect_error()) . '");
                    window.location.href = "Career.php";
                  </script>';
            exit();
        }

        // Prepare and bind
        $stmt = $con->prepare($category_query);
        $stmt->bind_param("sss", $job_name, $description, $qualification_info);
        if (!$stmt) {
            echo '<script>
                    alert("Preparation failed: ' . addslashes($con->error) . '");
                    window.location.href = "Career.php";
                  </script>';
            exit();
        }


        // Execute the statement
        if ($stmt->execute()) {
            // Move the uploaded file
                echo '<script>
                        alert("Career Added successfully.");
                        window.location.href = "Career.php";
                      </script>';
                exit();
        } else {
            echo '<script>
                    alert("Career insertion failed: ' . addslashes($stmt->error) . '");
                    window.location.href = "Career.php";
                  </script>';
            exit();
        }

}

if (isset($_POST['delete']) && isset($_POST['id'])) {
    $id = (int) $_POST['id'];
    $query = "DELETE FROM career WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: Career_edit.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($con);
    }
}

// Blog
    
if (isset($_POST["add_blog"])) {
    // Capture form data
    $title = $_POST["title"];
    $longdescription = $_POST["longdescription"];
    $client_name = $_POST["client_name"];
    $time = $_POST["time"];
    $category = $_POST["blog_category"];
    $status = isset($_POST['status']) && !empty($_POST['status']) ? $_POST['status'] : 'Inactive';
    $image = $_FILES["image"]["name"];

    // Allowed file extensions
    $allow_extensions = array("jpg", "jpeg", "png", "gif");
    $file_extension = pathinfo($image, PATHINFO_EXTENSION);

    // Check file extension
    if (!in_array($file_extension, $allow_extensions)) {
        echo '<script>
                alert("Only image files are allowed.");
                window.location.href = "blog.php";
              </script>';
        exit();
    } else {
        // Create a new filename
        $filename = time() . "." . $file_extension;

        // SQL query
        $category_query = "INSERT INTO blog (title, client_name,description, category, date, status, main_img) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";

        // Check database connection
        if (!$con) {
            echo '<script>
                    alert("Database connection failed: ' . addslashes(mysqli_connect_error()) . '");
                    window.location.href = "blog.php";
                  </script>';
            exit();
        }

        // Prepare and bind
        $stmt = $con->prepare($category_query);
        if (!$stmt) {
            echo '<script>
                    alert("Preparation failed: ' . addslashes($con->error) . '");
                    window.location.href = "blog.php";
                  </script>';
            exit();
        }

        $stmt->bind_param("sssssss", $title, $client_name, $longdescription, $category, $time, $status, $filename);

        // Execute the statement
        if ($stmt->execute()) {
            // Move the uploaded file
            if (move_uploaded_file($_FILES["image"]["tmp_name"], 'images/blog_images/' . $filename)) {
                echo '<script>
                        alert("Project inserted successfully.");
                        window.location.href = "blog.php";
                      </script>';
                exit();
            } else {
                echo '<script>
                        alert("Failed to move uploaded file.");
                        window.location.href = "blog.php";
                      </script>';
                exit();
            }
        } else {
            echo '<script>
                    alert("Project insertion failed: ' . addslashes($stmt->error) . '");
                    window.location.href = "blog.php";
                  </script>';
            exit();
        }
    }
}



//update blog

if (isset($_POST['update_blog'])) {
$user_id = $_POST['user_id'];
$title = $_POST['title'];
$longdescription = $_POST['longdescription'];
$client_name = $_POST['client_name'];
$category = $_POST['blog_category'];
$date = $_POST['date'];
$status = $_POST['status'];
$current_image = $_POST['main_img'];
$image = $_FILES['new_image']['name'];
$image_tmp_name = $_FILES['new_image']['tmp_name'];
$allowed_types = array('jpg', 'jpeg', 'png', 'gif');

// Handle file upload
if ($image) {
    $image_extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));

    if (in_array($image_extension, $allowed_types)) {
        $image_folder = 'images/blog_images/' . $image;
        if (!move_uploaded_file($image_tmp_name, $image_folder)) {
            die("Failed to upload image.");
        }
    } else {
        die("Invalid image format. Only JPG, JPEG, PNG, and GIF are allowed.");
    }
} else {
    $image = $current_image;
}

// Construct the SQL query with proper escaping to prevent SQL injection
$stmt = $con->prepare("UPDATE blog SET title = ?, description = ?, client_name = ?, category = ?, date = ?, status = ?, main_img = ? WHERE id = ?");
$stmt->bind_param('sssssssi', $title, $longdescription, $client_name, $category, $date, $status, $image, $user_id);

if (!$stmt->execute()) {
    die("Failed to update project: " . $stmt->error);
} else {
    header("Location: blog_edit.php"); // Redirect to a success page or the appropriate page
    exit;
}
}

