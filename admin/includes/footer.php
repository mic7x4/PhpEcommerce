</div><!-- Closing the Main content DIV-->
<footer class="text-center footer" > &copy; Copyright 2019-CrookzBootique</footer>
</div><!-- closing the Div in the body container fluid-->

<script>
    function updateSizes(){
        let sizeString = '';
        for(let i =1; i <= 6; i++){
            if(jQuery('#size'+i).val() != ''){
                sizeString += jQuery('#size'+i).val()+':'+jQuery('#qty'+i).val()+','; 
            }
        }

        jQuery('#sizes').val(sizeString);
    }


    function get_child_options(){
        var parentID = jQuery('#parent').val();
        jQuery.ajax({
            url : '/ecommerce/admin/parsers/child_categories.php',
            type : "POST",
            data : {parentID : parentID },
            success : function(data){
                jQuery('#child').html(data);
            },
            error : function(){alert("Something went wrong with the child option")}
        });
    }
    jQuery('select[name="parent"]').change(get_child_options);
</script>

</body>
</html>