<style>
.image-container {
    width: 100%;
    height: 200px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

.image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}

.product-image-wrapper {
    width: 80%; 
    height: 400px; 
    display: flex;
    flex-direction: column;
    justify-content: space-between; 
    margin: 0 auto; 
}

.single-products {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.productinfo {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.productinfo h5,
.productinfo p {
    margin: 10px 0;
}

.productinfo img {
    max-height: 200px; 
    width: auto;
}

.star-rating {
    color: #fd2323;
    font-size: 14px;
    margin-bottom: 10px;
}
</style>
<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-12 padding-right">
                <div class="features_items">
                    <h2 class="title text-center">Products</h2>
                    <?php
             if(isset($_POST['search'])) { 
                $query = "SELECT *, (SELECT AVG(RATING) FROM tblcustomerreview WHERE PROID = p.PROID) as avg_rating, (SELECT COUNT(*) FROM tblcustomerreview WHERE PROID = p.PROID) as review_count 
                          FROM `tblpromopro` pr , `tblproduct` p , `tblcategory` c
                          WHERE pr.`PROID`=p.`PROID` AND  p.`CATEGID` = c.`CATEGID`  AND PROQTY>0 
                          AND ( `CATEGORIES` LIKE '%{$_POST['search']}%' OR `PRODESC` LIKE '%{$_POST['search']}%' or `PROQTY` LIKE '%{$_POST['search']}%' or `PROPRICE` LIKE '%{$_POST['search']}%')";
              } elseif(isset($_GET['category'])) {
                $query = "SELECT *, (SELECT AVG(RATING) FROM tblcustomerreview WHERE PROID = p.PROID) as avg_rating, (SELECT COUNT(*) FROM tblcustomerreview WHERE PROID = p.PROID) as review_count 
                          FROM `tblpromopro` pr , `tblproduct` p , `tblcategory` c
                          WHERE pr.`PROID`=p.`PROID` AND  p.`CATEGID` = c.`CATEGID`  AND PROQTY>0 AND CATEGORIES='{$_GET['category']}'";
              } else {
                $query = "SELECT *, (SELECT AVG(RATING) FROM tblcustomerreview WHERE PROID = p.PROID) as avg_rating, (SELECT COUNT(*) FROM tblcustomerreview WHERE PROID = p.PROID) as review_count 
                          FROM `tblpromopro` pr , `tblproduct` p , `tblcategory` c
                          WHERE pr.`PROID`=p.`PROID` AND  p.`CATEGID` = c.`CATEGID`  AND PROQTY>0";
              }

            $mydb->setQuery($query);
            $res = $mydb->executeQuery();
            $maxrow = $mydb->num_rows($res);

            if ($maxrow > 0) { 
            $cur = $mydb->loadResultList();
           
            foreach ($cur as $result) { 
            ?>
                    <form method="POST" action="cart/controller.php?action=add">
                        <input type="hidden" name="PROPRICE" value="<?php echo $result->PROPRICE; ?>">
                        <input type="hidden" id="PROQTY" name="PROQTY" value="<?php echo $result->PROQTY; ?>">
                        <input type="hidden" name="PROID" value="<?php echo $result->PROID; ?>">
                        <div class="col-sm-4">
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                    <div class="productinfo text-center">
                                        <a href="index.php?q=single-item&id=<?php echo $result->PROID; ?>"> 
                                            <img src="<?php echo web_root.'admin/products/'. $result->IMAGES; ?>" alt="" />
                                        </a> 
                                        <h5>&#8369; <?php echo $result->PRODISPRICE; ?></h5>
                                        <p>Quantity: <em><?php echo $result->PROQTY; ?></em></p>
                                        <p><em><?php echo $result->PRODESC; ?></em></p>
                                        
                                        <!-- Star Rating Display -->
                                        <div class="star-rating">
    <?php
    // Handle NULL rating by defaulting to 0
    $avg_rating = is_null($result->avg_rating) ? 0 : (float)$result->avg_rating;
    $review_count = (int)$result->review_count;
    
    $full_stars = floor($avg_rating);
    $half_star = ($avg_rating - $full_stars) >= 0.5;
    $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);
    
    // Display stars
    for ($i = 1; $i <= $full_stars; $i++) {
        echo '<span class="fa fa-star"></span>';
    }
    if ($half_star) {
        echo '<span class="fa fa-star-half-alt"></span>';
    }
    for ($i = 1; $i <= $empty_stars; $i++) {
        echo '<span class="far fa-star"></span>';
    }
    
    // Format display with proper handling of NULL/zero cases
    $rating_display = $review_count > 0 ? 
        number_format($avg_rating, 1) : 
        '0.0';
    ?>
    <span>(<?php echo $rating_display; ?> / <?php echo $review_count; ?> reviews)</span>
</div>
                                        
                                        <button type="submit" name="btnorder" class="btn btn-default add-to-cart"><i
                                                class="fa fa-shopping-cart"></i>Add to cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php  
                    } 
                } else { 
                    echo '<h1>No Products Available</h1>';
                }
                ?>
                </div>
            </div>
        </div>
    </div>
</section>