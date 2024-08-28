<?php
  // Set the page title and include the necessary files
  $page_title = 'My profile';
  require_once('includes/load.php');
  
  // Check if the user has the required permission level to view this page
  page_require_level(3);
?>

<?php
  // Get the user ID from the URL
  $user_id = (int)$_GET['id'];

  // If the user ID is empty, redirect to the home page
  if(empty($user_id)):
    redirect('home.php', false);
  else:
    // If the user ID is valid, retrieve the user's profile information
    $user_p = find_by_id('users', $user_id);
  endif;
?>

<?php include_once('layouts/header.php'); ?>

<div class="row">
   <div class="col-md-4">
       <div class="panel profile">
         <!-- Display the user's profile information in a jumbotron -->
         <div class="jumbotron text-center bg-red">
            <!-- Display the user's profile image -->
            <img class="img-circle img-size-2" src="uploads/users/<?php echo $user_p['image'];?>" alt="">
            <!-- Display the user's name -->
           <h3><?php echo first_character($user_p['name']); ?></h3>
         </div>
         
         <!-- Display the 'Edit profile' option only if the logged-in user is viewing their own profile -->
        <?php if($user_p['id'] === $user['id']): ?>
         <ul class="nav nav-pills nav-stacked">
           <li><a href="edit_account.php"> <i class="glyphicon glyphicon-edit"></i> Edit profile</a></li>
         </ul>
       <?php endif; ?>
       </div>
   </div>
</div>

<?php include_once('layouts/footer.php'); ?>
