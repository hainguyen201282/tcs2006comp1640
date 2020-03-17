/**
 * @author Kishor Mali
 */


jQuery(document).ready(function(){
	
	jQuery(document).on("click touchend", ".deleteUser, .deleteStudent", function(){

		var userId = $(this).data("userid"),
			hitURL = baseURL + "deleteUser",
			currentRow = $(this);
		var data = { userId : userId };
			
		if ($(this).hasClass('deleteStudent')) {
			userId = $(this).data("studentid");
			hitURL = baseURL + "deleteStudent";
			data = { studentId : userId };
			confirmation = confirm("Are you sure to delete this student ?");
		} else {
			confirmation = confirm("Are you sure to delete this user ?");
		}
		
		if(confirmation)
		{
			jQuery.ajax({
			type : "POST",
			dataType : "json",
			url : hitURL,
			data : data
			}).done(function(data){
				console.log(data);
				currentRow.parents('tr').remove();
				if(data.status = true) { alert("User successfully deleted"); }
				else if(data.status = false) { alert("User deletion failed"); }
				else { alert("Access denied..!"); }
			});
		}
	});
	
	
	jQuery(document).on("click", ".searchList", function(){
		
	});
	
});
