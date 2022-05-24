document.addEventListener('DOMContentLoaded', function(){
    var link = document.querySelectorAll(".deleteData");
    for(i=0; i<link.length; i++){
        link[i].addEventListener('click', function(e){
            if(!confirm("Are You Sure Delete")){
                e.preventDefault();
            }
        })
    }
});