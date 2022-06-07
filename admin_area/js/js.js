$(function (){
    $(".select-all").click(function () {
        if (this.checked) {
            $(".checkbox").each(function (index, element) {
                element.checked = true;
            });
        } else {
            $(".checkbox").each(function (index, element) {
                element.checked = false;
            });
        }

    })
})