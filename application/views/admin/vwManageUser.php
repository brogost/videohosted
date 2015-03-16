<?php
$this->load->view('admin/vwHeader');
?>
<!--  
Load Page Specific CSS and JS here
Author : Abhishek R. Kaushik 
Downloaded from http://devzone.co.in
-->
<link href="<?php echo HTTP_CSS_PATH; ?>starter-template.css" rel="stylesheet">
 <style>
/*     .panel{
       margin-left: 55px;
       float: left;
    width: 500px;}
     */
     </style>
<div class="page-header container">
  <h1><small>Users</small></h1>
</div>
    <div class="container">
 
 <div class="panel panel-default" >
        <!-- Default panel contents -->
        <div class="panel-heading">User List <span style='float:right; margin-top: -7px;'><a href='#' class="btn btn-info">Add User</a></span></div>

        <!-- Table -->
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Username</th>
              <th>Email</th>
              <th>Login Date</th>
              <th>Status</th>
              <th>Process</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>abhishek</td>
              <td>abhishek@devzone.co.in</td>
              <td>2013-10-04 09:21:45</td>
              <td>Active</td>
              <td>
                  <a href='#' title='View'> <i class="fam-zoom"></i></a>
                 <a href='#' title='Edit'><i class="fam-user-edit"></i></a>
                 <a href='#' title='Block'><i class="fam-user-gray"></i></a>
                 <a href='#' title='Delete'><i class="fam-user-delete"></i></a>
              </td>
            </tr>
            <tr>
              <td>1</td>
              <td>abhishek</td>
              <td>abhishek@devzone.co.in</td>
              <td>2013-10-04 09:21:45</td>
              <td>Active</td>
              <td>
                  <a href='#' title='View'> <i class="fam-zoom"></i></a>
                 <a href='#' title='Edit'><i class="fam-user-edit"></i></a>
                 <a href='#' title='Block'><i class="fam-user-gray"></i></a>
                 <a href='#' title='Delete'><i class="fam-user-delete"></i></a>
              </td>
            </tr>
             <tr>
              <td>1</td>
              <td>abhishek</td>
              <td>abhishek@devzone.co.in</td>
              <td>2013-10-04 09:21:45</td>
              <td>Active</td>
              <td>
                  <a href='#' title='View'> <i class="fam-zoom"></i></a>
                 <a href='#' title='Edit'><i class="fam-user-edit"></i></a>
                 <a href='#' title='Block'><i class="fam-user-gray"></i></a>
                 <a href='#' title='Delete'><i class="fam-user-delete"></i></a>
              </td>
            </tr>
             <tr>
              <td>1</td>
              <td>abhishek</td>
              <td>abhishek@devzone.co.in</td>
              <td>2013-10-04 09:21:45</td>
              <td>Active</td>
            <td>
                  <a href='#' title='View'> <i class="fam-zoom"></i></a>
                 <a href='#' title='Edit'><i class="fam-user-edit"></i></a>
                 <a href='#' title='Block'><i class="fam-user-gray"></i></a>
                 <a href='#' title='Delete'><i class="fam-user-delete"></i></a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
 
<ul class="pagination">
        <li class="disabled"><a href="#"><<</a></li>
        <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">4</a></li>
        <li><a href="#">5</a></li>
        <li><a href="#">>></a></li>
     </ul>
    </div><!-- /.container -->
     <hr>
<?php
$this->load->view('admin/vwFooter');
?>