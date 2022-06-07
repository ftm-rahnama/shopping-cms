<?php
if(isset($_POST["answerId"])){
    $full_name=$_POST["full_name"];
}
else{
    showPost();
}
function showPost(){
   $product_id=$_GET["product"];
   $product=Product::getProductById($product_id);
   ?>

        <div class="card col-lg-12">
                    <div class="card-header">
                        <h4 ><?php echo $product->product_title;?>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                        <div class="col-lg-6 d-block">
                        <img src="<?php echo $product->product_img; ?>" alt="">
                        </div>
                        <div class="col-lg-6">
                        <span class="text-muted">
                            گروه:
                             <p class="text-dark">
                            <?php
                            $cat=Category::getCategoryById($product->product_cat);
                            echo $cat->category_name; ?>
                             </p>
                        </span>
                            <span class="text-muted">
                            سایز:
                             <p class="text-dark">
                                 <?php echo $product->product_size; ?>
                             </p>
                        </span>
                        <span class="text-muted">
                            برند:
                             <p class="text-dark">
                                 <?php
                                 $brand=Brand::getBrandyById($product->product_brand);
                                  echo $brand->brand_title; ?>
                             </p>
                        </span>
                        <span class="text-muted">
                            ویژگی ها :
                            <p class="text-dark">
                             <?php
                             $descs=explode("\n",$product->product_desc);
                             foreach ($descs as $desc) {
                                 echo $desc;?>
                                 <br>
                                 <?php }?>
                            </p>
                        </span>

                    </div>
                    </div>
        </div>
            <div class="card-footer bg-light">
                <span class="d-block"> قیمت:  <?php echo $product->product_price?> تومان </span>
                <a href="./?buy=<?php echo $product->id?>" class="hover color">
                    <i class="fa fa-shopping-cart"></i>افزودن به سبد
                </a>
            </div>
    </div>
    <?php
    $comments=Comment::getCommentsByPost_id($product_id);
    if($comments){
    foreach ($comments as $comment) {?>
        <div class="col-lg-9 ">
            <div class="card ">
                <div class="card-header text-right">
                      <span class="color">
                    <?php
                        echo $comment->full_name;
                        ?>
                      </span>
                    <span class="text-muted">
                        <?php
                        $creation=convertDate($comment->comment_time);
                        echo " در ".$creation["day"]." ".$creation["month_name"]." ".$creation["year"];
                        echo "در ساعت ".$creation["hour"].":".$creation["minute"]  . " گفته:";
                       ?>
                    </span>
                </div>
                <span class="p-3">
                <?php echo $comment->comment;?>
                    </span>
            </div>
    </div>
    <?php }}?>
    <div class="col-lg-9">
        <div class="card bg-white">
            <div class="card-header">
                <h6 class="card-title ">دیدگاه خود را در مورد محصول بنویسید</h6>
            </div>

            <div class="card-body">
                <form method="post" id="insert_product" >
                    <div class="form-group">
                        <label for="">نام</label>
                        <input type="text" class="form-control" id="" name="name" placeholder="نام">
                    </div>
                    <div class="form-group">
                        <label for="">ایمیل</label>
                        <input type="text" class="form-control" id="" name="email" placeholder="ایمیل">
                    </div>
                    <div class="form-group">
                        <label for="">دیدگاه</label>
                        <textarea class="form-control" id="" name="comment" rows="5"></textarea>
                    </div>
                    <input type="submit" class="btn btn-dark" form="insert_product" name="submit" value="ارسال">

                </form>
            </div>
        </div>
    </div>
    <?php if (isset($_POST["submit"])) {
        if ($product->allow_comment == 1) {
            $comment = Comment::insertComment($_POST["name"], $_POST['email'], $_POST["comment"], $product_id, 0, $_SERVER["REMOTE_ADDR"]);
        }
    }
    ?>

<?php }?>