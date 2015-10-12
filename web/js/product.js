
$(document).ready(function()
{
    console.log($("#product_detail"));
    $("#product_detail").on("click",".delete",function(event)
    {
        event.preventDefault();
        if(confirm("Etes-vous sur de vouloir supprimer ce produit?")) {
            var aDelete = $(this);
            var urlDelete = aDelete.attr("href");
            $.ajax({
                type: "GET",
                url: urlDelete
            })
            .done(function()
            {
                aDelete.closest("tr").fadeOut(600,function()
                {
                    $(this).remove();
                });
            });
        }
    })
})