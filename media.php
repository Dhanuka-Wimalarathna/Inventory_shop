<?php
  // Set the page title and include the necessary files
  $page_title = 'All Images';
  require_once('includes/load.php');
  
  // Check if the user has the required permission level to view this page
  page_require_level(2);
?>

<?php 
  // Retrieve all media files from the database
  $media_files = find_all('media');
?>

<?php
  // Handle the file upload process
  if(isset($_POST['submit'])) {
    // Create a new Media object
    $photo = new Media();
    
    // Upload the file using the Media class
    $photo->upload($_FILES['file_upload']);
    
    // Process the uploaded media
    if($photo->process_media()){
        // Success: Display a success message and redirect to the media page
        $session->msg('s','Photo has been uploaded.');
        redirect('media.php');
    } else {
        // Failure: Display an error message and redirect to the media page
        $session->msg('d',join($photo->errors));
        redirect('media.php');
    }
  }
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-6">
    <!-- Display any messages (e.g., success or error messages) -->
    <?php echo display_msg($msg); ?>
  </div>

  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <span class="glyphicon glyphicon-camera"></span>
        <span>All Photos</span>
        
        <!-- Form for uploading a new photo -->
        <div class="pull-right">
          <form class="form-inline" action="media.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <div class="input-group">
                <!-- File input for selecting photos to upload -->
                <span class="input-group-btn">
                  <input type="file" name="file_upload" multiple="multiple" class="btn btn-primary btn-file"/>
                </span>
                <!-- Submit button to upload the selected photos -->
                <button type="submit" name="submit" class="btn btn-default">Upload</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      
      <div class="panel-body">
        <!-- Table displaying the list of all uploaded photos -->
        <table class="table">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th class="text-center">Photo</th>
              <th class="text-center">Photo Name</th>
              <th class="text-center" style="width: 20%;">Photo Type</th>
              <th class="text-center" style="width: 50px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <!-- Loop through each media file and display its details in a table row -->
            <?php foreach ($media_files as $media_file): ?>
            <tr class="list-inline">
              <td class="text-center"><?php echo count_id();?></td>
              <td class="text-center">
                <!-- Display the photo thumbnail -->
                <img src="uploads/products/<?php echo $media_file['file_name'];?>" class="img-thumbnail" />
              </td>
              <td class="text-center">
                <!-- Display the photo name -->
                <?php echo $media_file['file_name'];?>
              </td>
              <td class="text-center">
                <!-- Display the photo type -->
                <?php echo $media_file['file_type'];?>
              </td>
              <td class="text-center">
                <!-- Action button to delete the photo -->
                <a href="delete_media.php?id=<?php echo (int) $media_file['id'];?>" class="btn btn-danger btn-xs" title="Delete">
                  <span class="glyphicon glyphicon-trash"></span>
                </a>
              </td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include_once('layouts/footer.php'); ?>
