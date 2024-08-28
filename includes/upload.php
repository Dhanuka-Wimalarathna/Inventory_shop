<?php

class Media {

  public $imageInfo;
  public $fileName;
  public $fileType;
  public $fileTempPath;
  // Paths for storing uploaded files
  public $userPath = SITE_ROOT.DS.'..'.DS.'uploads/users';
  public $productPath = SITE_ROOT.DS.'..'.DS.'uploads/products';

  public $errors = array();
  
  // Array of possible upload errors
  public $upload_errors = array(
    0 => 'There is no error, the file uploaded successfully',
    1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
    2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive specified in the HTML form',
    3 => 'The uploaded file was only partially uploaded',
    4 => 'No file was uploaded',
    6 => 'Missing a temporary folder',
    7 => 'Failed to write file to disk.',
    8 => 'A PHP extension stopped the file upload.'
  );
  
  // Allowed file extensions
  public $upload_extensions = array(
   'gif',
   'jpg',
   'jpeg',
   'png',
  );

  // Check if file extension is allowed
  public function file_ext($filename){
    $ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
    if(in_array($ext, $this->upload_extensions)){
      return true;
    }
    return false; // Return false if extension is not allowed
  }

  // Handle the file upload process
  public function upload($file)
  {
    if(!$file || empty($file) || !is_array($file)):
      $this->errors[] = "No file was uploaded.";
      return false;
    elseif($file['error'] != 0):
      $this->errors[] = $this->upload_errors[$file['error']];
      return false;
    elseif(!$this->file_ext($file['name'])):
      $this->errors[] = 'Invalid file format.';
      return false;
    else:
      $this->imageInfo = getimagesize($file['tmp_name']);
      $this->fileName  = basename($file['name']);
      $this->fileType  = $this->imageInfo['mime'];
      $this->fileTempPath = $file['tmp_name'];
      return true;
    endif;
  }

  // Validate file before processing
  public function process(){
    if(!empty($this->errors)):
      return false;
    elseif(empty($this->fileName) || empty($this->fileTempPath)):
      $this->errors[] = "The file location was not available.";
      return false;
    elseif(!is_writable($this->productPath)):
      $this->errors[] = $this->productPath." must be writable.";
      return false;
    elseif(file_exists($this->productPath."/".$this->fileName)):
      $this->errors[] = "The file {$this->fileName} already exists.";
      return false;
    else:
      return true;
    endif;
  }

  // Process and move the uploaded media file
  public function process_media(){
    if(!empty($this->errors)){
      return false;
    }
    if(empty($this->fileName) || empty($this->fileTempPath)){
      $this->errors[] = "The file location was not available.";
      return false;
    }
    if(!is_writable($this->productPath)){
      $this->errors[] = $this->productPath." must be writable.";
      return false;
    }
    if(file_exists($this->productPath."/".$this->fileName)){
      $this->errors[] = "The file {$this->fileName} already exists.";
      return false;
    }
    if(move_uploaded_file($this->fileTempPath, $this->productPath.'/'.$this->fileName)){
      if($this->insert_media()){
        unset($this->fileTempPath);
        return true;
      }
    } else {
      $this->errors[] = "The file upload failed, possibly due to incorrect permissions on the upload folder.";
      return false;
    }
  }

  // Process and move the user image
  public function process_user($id){
    if(!empty($this->errors)){
      return false;
    }
    if(empty($this->fileName) || empty($this->fileTempPath)){
      $this->errors[] = "The file location was not available.";
      return false;
    }
    if(!is_writable($this->userPath)){
      $this->errors[] = $this->userPath." must be writable.";
      return false;
    }
    if(!$id){
      $this->errors[] = "Missing user ID.";
      return false;
    }
    $ext = explode(".", $this->fileName);
    $new_name = randString(8).$id.'.' . end($ext);
    $this->fileName = $new_name;
    if($this->user_image_destroy($id)){
      if(move_uploaded_file($this->fileTempPath, $this->userPath.'/'.$this->fileName)){
        if($this->update_userImg($id)){
          unset($this->fileTempPath);
          return true;
        }
      } else {
        $this->errors[] = "The file upload failed, possibly due to incorrect permissions on the upload folder.";
        return false;
      }
    }
  }

  // Update user image in the database
  private function update_userImg($id){
    global $db;
    $sql = "UPDATE users SET";
    $sql .= " image='{$db->escape($this->fileName)}'";
    $sql .= " WHERE id='{$db->escape($id)}'";
    $result = $db->query($sql);
    return ($result && $db->affected_rows() === 1 ? true : false);
  }

  // Delete old user image
  public function user_image_destroy($id){
    $image = find_by_id('users', $id);
    if($image['image'] === 'no_image.png'){
      return true;
    } else {
      unlink($this->userPath.'/'.$image['image']);
      return true;
    }
  }

  // Insert new media record into the database
  private function insert_media(){
    global $db;
    $sql  = "INSERT INTO media (file_name, file_type)";
    $sql .= " VALUES ";
    $sql .= "(
              '{$db->escape($this->fileName)}',
              '{$db->escape($this->fileType)}'
              )";
    return ($db->query($sql) ? true : false);
  }

  // Delete media file and record
  public function media_destroy($id, $file_name){
    $this->fileName = $file_name;
    if(empty($this->fileName)){
      $this->errors[] = "The photo file name is missing.";
      return false;
    }
    if(!$id){
      $this->errors[] = "Missing photo ID.";
      return false;
    }
    if(delete_by_id('media', $id)){
      unlink($this->productPath.'/'.$this->fileName);
      return true;
    } else {
      $this->error[] = "Photo deletion failed or missing parameters.";
      return false;
    }
  }
}

?>

