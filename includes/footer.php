</div><!-- Closing the Main content DIV-->
<footer class="text-center footer" > &copy; Copyright 2019-CrookzBootique</footer>
</div><!-- closing the Div in the body container fluid-->


<script>
function detailsmodal(id){
    var data = {"id":id};
    jQuery.ajax({
        url : '/ecommerce/includes/detailsmodal.php',
        method : "post",
        data : data,
        success:function(data){
            jQuery('body').append(data);
            jQuery('#details-modal').modal('toggle');
        },
        error:function(){
            alert("Something Went Wrong!!");
        }
    });
}
</script>
</body>
</html>