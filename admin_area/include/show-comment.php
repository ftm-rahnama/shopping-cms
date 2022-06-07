
<?php
if(isset($_GET["show-comment"]) and $_GET["show-comment"]=="replied"){
    echo '<script>
$(function (){
    swal({
    title:"باتشکر",text:"پاسخ کامنت ثبت شد",icon:"success",buttons:"بستن",timer:3000
    });
})
</script>';
}
if(isset($_GET["del_comm"])) {
$id = $_GET["del_comm"];
$delSelectComment = Comment::delSelectComment($id);
if ($delSelectComment) {
echo '<script>
    $(function (){
        swal({
            title:"باتشکر",text:"کامنت انتخابی حذف شد",icon:"info",buttons:"بستن",timer:3000
        });
    })
</script>';
}
}
if (isset($_POST["del-all"])) {
    if (isset($_POST["checkbox"])) {
        $count = count($_POST["checkbox"]);
        $check = $_POST["checkbox"];
        $delAllComment =Comment:: delAllcomments($check, $count);
        if ($delAllComment) {
            echo '<script>
$(function (){
    swal({
    title:"باتشکر",text:"اطلاعات با موفقیت حذف گردید",icon:"success",buttons:"بستن",timer:3000
    });
})
</script>';
        }
        else {
            echo '<script>
       $(function (){
    swal({
    title:"متسفانه",text:"گزینه برای حذف موجود نیست",icon:"error",buttons:"بستن",timer:3000
    });
})
</script>';
        }
    }
}?>
<div class="card">
    <div class="card-header ">
        <h4 class="card-title text-center ">کامنت های کاربران</h4>
    </div>
    <div class="card-body">
        <ul class="nav nav-pills nav-fill bg-transparent">
            <li class="nav-item text-secondary">
                <a class="nav-link active bg" href="./?show-comment">همه</a>
            </li>
                    <li class="nav-item text-secondary">
                        <a class="nav-link text-secondary" href="./?show-comment=main">
                           کامنت های اصلی</a>
                    </li>
            <li class="nav-item text-secondary">
                        <a class="nav-link text-secondary" href="./?show-comment=reply">
                           پاسخ ها</a>
                    </li>

            <li class="nav-item">
                <form action="#" method="post">
                    <button class="btn btn-outline-danger btn-sm "  name="del-all"><i class="fa fa-trash-alt"></i>حذف کلی</button>
                    <a href="./?add-comment" class="btn btn-outline-success btn-sm" ><i class="fa fa-plus "></i> افزودن کامنت</a>

            </li>
        </ul>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <table class="table table-striped table-bordered text-center">
            <thead>
            <tr>
                <th scope="col"><input type="checkbox" class="select-all" name="checkbox"></th>
                <th scope="col"><a href="./?show-comment=id" class="hover color"> کامنت id<i class="fa fa-chevron-down px-1"></i></a></th>
                <th scope="col">نام</th>
                <th scope="col">ایمیل</th>
                <th scope="col"><a href="./?show-comment=pro-id" class="hover color"> محصول id<i class="fa fa-chevron-down px-1"></i></a></th>
                <th scope="col">نام محصول</th>
                <th scope="col">دیدگاه</th>
                <th scope="col"><a href="./?show-comment=time" class="hover color">زمان<i class="fa fa-chevron-down px-1"></i></a></th>
                <th scope="col">وضعیت کامنت</th>
                <th scope="col">ip کاربر</th>
                <th scope="col">پاسخ ها</th>
                <th scope="col">عملیات</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if(isset($_GET["show-comment"])and $_GET["show-comment"]>0){
                $section=$_GET["show-comment"];
            }
            else $section=1;
            if(isset($_GET["show-comment"])and ($_GET["show-comment"])=="main"){
            $condition="parent_id=0";
            }
            elseif(isset($_GET["show-comment"])and ($_GET["show-comment"])=="reply"){
            $condition="parent_id>0";
            }
            else $condition=1;
            if(isset($_GET["show-comment"])and $_GET["show-comment"]=="id"){
                $order="id";
            }
            else if(isset($_GET["show-comment"])and $_GET["show-comment"]=="time"){
                $order="comment_time";
            }
            else if(isset($_GET["show-comment"])and $_GET["show-comment"]=="pro-id"){
                $order="product_id";
            }
            else $order="id";
            $start=($section-1)* MAX_POST;
            $comments=Comment::getAllcomments($condition,MAX_POST,$start,$order);
            if($comments){
                foreach ($comments as $comment): ?>
                    <tr>
                        <th><input type="checkbox" class="checkbox" name="checkbox[]" value="<?php echo $comment->id ?>"></th>
                        <th><?php echo $comment->id?></th>
                        <th><?php echo $comment->full_name ?></th>
                        <th><?php echo $comment->email ?></th>
                        <th><?php echo $comment->product_id;?></th>
                        <th><?php
                            $product=Product::getProductById($comment->product_id);
                            echo $product->product_title?></th>
                        <th><?php echo $comment->comment?></th>
                        <th> <?php
                            $creation=convertDate($comment->comment_time);
                            echo $creation["day"]." ".$creation["month_name"]." ".$creation["year"];
                            echo "در ساعت ".$creation["hour"].":".$creation["minute"];
                            ?>
                        </th>
                        <th><?php
                            if($comment->parent_id==0) echo '<span class="badge badge-success">کامنت اصلی</span>';
                            else echo '<span class="badge badge-warning">پاسخ</span>';
                            ?></th>
                        <th><?php echo $comment->user_ip?></th>

                        <th><?php
                            $replies= Comment::getCommentByParentId($comment->id);
                            if($replies) {
                                foreach ($replies as $reply){
                                    echo "<span class='text-muted'>".$reply->full_name." :</span>";
                            echo $reply->comment."<hr>";
                                }
                            } ?></th>
                        <th>
                            <a href="?update_comment_id=<?php echo  $comment->id; ?>" class="text-info px-1"><i class="fa fa-reply"></i></a>
                            <a href="?del_comm=<?php echo $comment->id; ?>" class="text-danger"><i class="fa fa-trash-alt"></i></a>
                        </th>
                    </tr>
                <?php endforeach; }
            else{ echo "<div class='alert alert-warning'>متاسفانه کامنتی برای نمایش وجود ندارد</div>"; } ?>
            </tbody>
        </table>
    </div>
</div>
<?php
if($comments){
$totalProduct=count(Comment::getAllcomments($condition));
$totalSection=ceil($totalProduct/MAX_POST);?>
<div class="col-lg-12">
    <div class="paging">
        <p> صفحه ی <?php echo $section?> از <?php echo $totalSection?></p>
        <ul id="paging">
            <?php
            for ($index=1;$index<=$totalSection;$index++){
                if($index==$section)$class="class=active";
                else $class="";
                $sec=$index;
                echo "<li><a href=\"./?show-comment=$sec\" $class>$index</a></li>";
            }
            ?>
        </ul>
    </div>

    <?php }?>
