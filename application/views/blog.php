<style>
  * {
    box-sizing: border-box;
  }

  /* Create three equal columns that floats next to each other */
  .column {
    float: left;
    width: 33.33%;
    padding: 10px;
    height: 300px;
    /* Should be removed. Only for demonstration */
  }

  /* Clear floats after the columns */
  .row:after {
    content: "";
    display: table;
    clear: both;
  }
</style>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <i class="fa fa-users"></i> Blog Management
      <small>Add, Edit, Delete</small>
    </h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-xs-12 text-right">
        <div class="form-group">
          <a class="btn btn-primary" href="<?php echo base_url(); ?>addNewBlog"><i class="fa fa-plus"></i> Add New</a>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Blog posts</h3>
            <div class="box-tools">
              <form action="<?php echo base_url() ?>blogListing" method="POST" id="searchList">
                <div class="input-group">
                  <input type="text" name="searchText" value="<?php echo $searchText; ?>" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search" />
                  <div class="input-group-btn">
                    <button class="btn btn-sm btn-default searchList"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
              <tr>
                <th>Blog Name</th>
                <th>Topic</th>
                <th>Content</th>
                <th class="text-center">Actions</th>
              </tr>
              <?php
              if (!empty($blogRecords)) {
                foreach ($blogRecords as $record) {
              ?>
                  <tr>
                    <td><?php echo $record->title ?></td>
                    <td><?php echo $record->topic ?></td>
                    <td><?php echo $record->content ?></td>
                    <td class="text-center">
                      <a class="btn btn-sm btn-primary" href="<?= base_url() . 'login-history/' . $record->id; ?>" title="Login history"><i class="fa fa-history"></i></a> |
                      <a class="btn btn-sm btn-info" href="<?php echo base_url() . 'editViewBlog/' . $record->id; ?>" title="Edit"><i class="fa fa-pencil"></i></a>
                      <a class="btn btn-sm btn-danger deleteBlog" href="#" data-blogid="<?php echo $record->id; ?>" title="Delete"><i class="fa fa-trash"></i></a>
                    </td>
                  </tr>
              <?php
                }
              }
              ?>
            </table>
          </div>
          <div class="box-footer clearfix">
            <?php echo $this->pagination->create_links(); ?>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script type="text/javascript" charset="utf-8">
  $(document).ready(function() {
    $(document).on("click", ".deleteBlog", function() {

      var id = $(this).data("blogid"),
          hitURL = baseURL + "deleteBlog",
          currentRow = $(this);

      var confirmation = confirm("Are you sure to delete this blog?");

      if (confirmation) {
        $.ajax({
          type: "POST",
          url: hitURL,
          data: {
            blogId: id
          }
        }).done(function(data) {
          console.log(data);

          currentRow.parents('tr').remove();

          if (data.status = true) {
            alert("Blog successfully deleted");
          } else if (data.status = false) {
            alert("Blog delete failed");
          } else {
            alert("Access denied..!");
          }
        });
      }
    });
  });
</script>