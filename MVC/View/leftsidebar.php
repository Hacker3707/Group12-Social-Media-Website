<ul class="nav flex-column">
  <li class="nav-item">
    <a class="nav-link active" href="#"><img src="../../Materials/Picture/Passo.png" class="rounded-circle" width="30" height="30" alt="..."></a>
  </li>
  <li class="nav-item">
    <a href="index.php?controller=user&action=profile&id=<?= $_SESSION['user_id'] ?>" class="nav-link">Profile</a>
  </li>
  <li class="nav-item">
    <a href="index.php?controller=group&action=myGroups" class="nav-link">Groups</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#">Setting</a>
  </li>
  <li class="nav-item">
    <a class="nav-link disabled" href="#">Switch to Admin View</a>
  </li>
</ul>