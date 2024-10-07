<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>

  .img-related{
    margin-left:25px;
  }

  .tp{
    margin-top:30px;
  }

  .mt{
    margin-top:30px;
  }

  .formatted-description {
  margin-left: auto;
  margin-right: auto;
  max-width: 70%;
  padding: 0 15px; 
}

.p-desc {
  font-family: Arial, sans-serif;
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        line-height: 1.6;
        white-space: pre-wrap;
}

.preserve-newlines {
    white-space: pre-wrap;
    word-wrap: break-word;
}

.customer-reviews {
    display: flex;
    justify-content: center; /* Align items closer to the center */
    align-items: center;
    padding: 20px;
    max-width: 100%;
    border: 1px solid #eaeaea;
}

.review-summary, .write-review-btn-container {
    flex-basis: 22%; /* Adjusted width to allow more space for center */

}

.rating-breakdown {
    flex-basis: 25%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    text-align: center;
}

.rating-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 3px; /* Reduced spacing between items */
}

.progress-bar-container {
    background-color: #e0e0e0;
    flex: 1;
    height: 10px;
    margin: 0 5px; /* Reduced spacing for progress bar */
    overflow: hidden;
    cursor:pointer;
}

.progress-bar {
    background-color: #fd2323;
    height: 100%;
    width: 0; /* Dynamically set via inline styles */
    cursor:pointer;
}

.write-review-btn {
    display: inline-block;
    background-color: #fd2323;
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 5px;
    font-size: 16px;
    border:none;
   
}

.write-review-btn-container {
    text-align: right;
    margin-left: 10px; /* Reduced margin */
   
}

/* Further center alignment and tighter spacing */
.review-list {
    display: flex;
    flex-direction: column;
    justify-content: center;
    flex-basis: 22%; /* Same width as review-summary */
    padding: 20px;
    border: 1px solid #eaeaea;
}

.review {
    margin-bottom: 20px;
    border-bottom: 1px solid #eaeaea;
    padding-bottom: 15px;
  }

  .review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 5px;
  }

  .reviewer-name {
    display: flex;
    align-items: center;
  }

  .reviewer-name i {
    margin-right: 5px;
    color: black;
  }

  .review-date {
    font-size: 0.9em;
    color: #777;
  }

  .star-rating {
    color:#fd2323;
    margin-bottom: 5px;
  }

  .review-text {
    margin-top: 5px;
  }

.review p {
    margin: 0;
}
.rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: center;
    margin: 10px 0;
}

.rating input {
    display: none;
}

.rating label {
    font-size: 2em;
    color: #ccc;
    cursor: pointer;
}

.rating input:checked ~ label,
.rating input:hover ~ label {
    color: #fd2323;
}

.bg{
  background-color:#fd2323;
  color:white;
}

.write-review-form {
        display: none;
        max-width: 500px;
        margin: 20px auto;
        padding: 20px;
        border-radius: 8px;
    }

    .write-review-form h4 {
        text-align: center;
        margin-bottom: 20px;
    }

    .write-review-form form {
        display: flex;
        flex-direction: column;
    }

    .write-review-form label {
        margin-top: 10px;
    }

    .write-review-form input[type="text"],
    .write-review-form textarea {
        width: 100%;
        padding: 8px;
        margin-top: 5px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .write-review-form .rating {
        display: flex;
        justify-content: center;
        margin: 10px 0;
    }

    .write-review-form .btn-container {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
    }

    .write-review-form .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .write-review-form .submit {
        background-color: #fd2323;
        color: white;
    }

    .write-review-form .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

.field{
  margin-top:10px;
}

.star-label{
    color: #fd2323;
}

@media (max-width: 767px) {
    .customer-reviews {
        flex-direction: column;
        align-items: center;
    }

    .review-summary, .rating-breakdown, .write-review-btn-container {
        flex-basis: 100%;
        width: 100%;
        margin-bottom: 20px;
        text-align: center;
    }

    .rating-item {
        justify-content: center;
    }

    .progress-bar-container {
        width: 60%;
    }

    .write-review-btn-container {
        text-align: center;
        margin-left: 0;
    }
}

.product-item {
    text-align: center;
    margin-bottom: 20px;
}

.product-item img {
    display: block;
    margin: 0 auto;
    max-width: 60%;
    height: auto;
}

.product-item p {
    margin-top: 10px;
    color: black;
    text-align: center;
}

</style>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

define('DB_SERVER', 'localhost');
define('DB_USER', 'u510162695_dried');
define('DB_PASS', '1Dried_password');
define('DB_NAME', 'u510162695_dried');
$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$PROID = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Function to generate star rating HTML
function generateStarRating($rating) {
    $fullStar = "★";
    $emptyStar = "☆";
    $output = str_repeat($fullStar, $rating) . str_repeat($emptyStar, 5 - $rating);
    return $output;
}

$sql = "SELECT * FROM tblcustomerreview WHERE PROID = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $PROID);
$stmt->execute();
$result = $stmt->get_result();

$total_reviews = 0;
$total_rating = 0;
$rating_counts = array(5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0);

while ($row = $result->fetch_assoc()) {
    $total_reviews++;
    $total_rating += $row['RATING'];
    $rating_counts[$row['RATING']]++;
}

$average_rating = $total_reviews > 0 ? round($total_rating / $total_reviews, 2) : 0;
?>

<?php


$PROID =   $_GET['id'];
$query = "SELECT * FROM `tblpromopro` pr , `tblproduct` p , `tblcategory` c
            WHERE pr.`PROID`=p.`PROID` AND  p.`CATEGID` = c.`CATEGID`  AND p.`PROID`=" . $PROID;
$mydb->setQuery($query);
$cur = $mydb->loadResultList();


foreach ($cur as $result) {

?>

  <!-- Portfolio Item Row -->
  <form method="POST" action="cart/controller.php?action=add">

  <div class="container">
  <div class="row">
    <div class="col-lg-7 col-md-12 mb-4">
      <div class="text-center text-lg-end">
        <img class="img-fluid rounded" style="max-width: 330px;" src="<?php echo web_root . 'admin/products/' . $result->IMAGES; ?>" alt="Product Image">
      </div>
    </div>
    <div class="col-12 text-center">
      <input type="hidden" name="PROPRICE" value="<?php echo $result->PRODISPRICE; ?>">
      <input type="hidden" id="PROQTY" name="PROQTY" value="<?php echo $result->PROQTY; ?>">
      <input type="hidden" name="PROID" value="<?php echo $result->PROID; ?>">
      <p><?php echo $result->CATEGORIES; ?></p>
      <ul class="list-unstyled">
        <li>Type - <?php echo $result->PRODESC; ?></li>
        <li>Stock - <?php echo $result->PROQTY; ?> Quantity</li>
        <li>Price - &#8369;<?php echo $result->PROPRICE; ?></li>
       
      </ul>
      <button type="submit" name="btnorder" class="btn btn-default add-to-cart"><i
      class="fa fa-shopping-cart"></i>Add to cart</button>
    </div>
  </div>

</div>
    <?php } ?>
    </div>
    </form>
   
    <div class="row">
        <div class="col-12 text-center mt-5 tp">
            <h3>Product Description</h3>
        </div>
    </div>
    <div class="row">
    <div class="col-12 text-center">
      <?php $description = property_exists($result, 'Description') ? $result->Description : 'No description available.';

// Then, where you're displaying the description:
echo '<p>' .  nl2br($description) . '</p>';
    ?>

   
    </div>
  </div>

 <!-- Customer Reviews Section -->
 <div class="customer-reviews">
    <!-- Review Summary Section (Left) -->
    <div class="review-summary">
    <p><strong><?php echo number_format(round($average_rating, 1), 1); ?></strong> out of 5</p>
        <p>Based on <?php echo $total_reviews; ?> reviews</p>
    </div>

    <!-- Rating Breakdown (Center) -->
    <div class="rating-breakdown">
    <?php for ($i = 5; $i >= 1; $i--): 
        $percentage = $total_reviews > 0 ? ($rating_counts[$i] / $total_reviews) * 100 : 0;
    ?>
        <div class="rating-item" onclick="filterReviews(<?php echo $i; ?>)">
            <span class="star-label"><?php echo str_repeat('★', $i) . str_repeat('☆', 5 - $i); ?></span>
            <div class="progress-bar-container">
                <div class="progress-bar" style="width: <?php echo $percentage; ?>%;"></div>
            </div>
            <span class="rating-count"><?php echo $rating_counts[$i]; ?></span>
        </div>
    <?php endfor; ?>
</div>

    <div class="write-review-btn-container">
        <button id="toggleReviewBtn" class="write-review-btn" onclick="toggleReviewForm()">Write a review</button>
    </div>
</div>

<!-- Write Review Form (Updated for centering) -->
<div id="reviewForm" class="write-review-form">
    <h4>Write a Review</h4>
    <form method="POST" action="submit_review.php" id="reviewSubmitForm">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <input type="hidden" name="proid" value="<?php echo $PROID; ?>">

        <div>
            <h5 for="rating" class="text-center">Rating</h5>
            <div class="rating">
                <input type="radio" name="rating" value="5" id="star5" required><label for="star5">★</label>
                <input type="radio" name="rating" value="4" id="star4"><label for="star4">★</label>
                <input type="radio" name="rating" value="3" id="star3"><label for="star3">★</label>
                <input type="radio" name="rating" value="2" id="star2"><label for="star2">★</label>
                <input type="radio" name="rating" value="1" id="star1"><label for="star1">★</label>
            </div>
        </div>
        <div class="field">
            <input type="text" id="name" name="name" placeholder="Enter your name" required>
        </div>
        <div class="field">
            <textarea id="review" name="reviewtext" placeholder="Write your comments here" required></textarea>
        </div>
        <div class="btn-container">
            <button type="submit" class="btn bg">Submit Review</button>
            <button type="button" class="btn btn-secondary" id="hideReview" onclick="hideReviewForm()">Cancel</button>
        </div>
    </form>
</div>



<div class="review-list" id="reviewList">
    <?php
    // Fetch reviews for this product
    $review_query = "SELECT * FROM tblcustomerreview WHERE PROID = $PROID ORDER BY REVIEWDATE DESC";
    $review_result = $con->query($review_query);

    // Review List
    if ($review_result && $review_result->num_rows > 0) {
        $review_count = 0;
        while ($row = $review_result->fetch_assoc()) {
            $review_count++;
            $display_style = $review_count > 3 ? 'style="display:none;"' : '';
            echo '<div class="review" data-rating="' . $row['RATING'] . '" ' . $display_style . '>';
            echo '<div class="review-header">';
            echo '<div class="reviewer-name">';
            echo '<i class="fa fa-user-circle"></i> <strong>' . htmlspecialchars($row['CUSTOMERNAME']) . '</strong>';
            echo '</div>';
            echo '<span class="review-date">' . date('Y-m-d', strtotime($row['REVIEWDATE'])) . '</span>';
            echo '</div>';
            echo '<div class="star-rating">' . str_repeat('★', $row['RATING']) . str_repeat('☆', 5 - $row['RATING']) . '</div>';
            echo '<p class="review-text">' . htmlspecialchars($row['REVIEWTEXT']) . '</p>';
            echo '</div>';
        }
        if ($review_count > 3) {
            echo '<p id="seeFullReviews" class="text-center"><a href="#" onclick="showAllReviews()">See full reviews</a></p>';
        }
    } else {
        echo '<p id="noReviews" class="text-center" style="font-weight:bold;">No reviews yet. Be the first to review this product!</p>';
    }
    ?>
</div>


<?php
$stmt->close();
$con->close();
?>
</div>


<!-- <script>
function showReviewForm() {
    // Implement the logic to show a review form
    alert('Review form will be shown here');
}
</script> -->
<?php


if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

  <?php
  $query = "SELECT * FROM `tblpromopro` pr , `tblproduct` p , `tblcategory` c
            WHERE pr.`PROID`=p.`PROID` AND  p.`CATEGID` = c.`CATEGID`  AND `CATEGORIES`='" . $result->CATEGORIES . "' limit 4";
  $mydb->setQuery($query);
  $cur = $mydb->loadResultList();
  ?>
  <!-- Related Projects Row -->
  <div class="row mt">

    <div class="col-lg-12">
      <h4 class="page-header text-start" style="margin-left:15px;">You may also like</h4>
    </div>
    <?php

    foreach ($cur as $result) {

    ?>
    <div class="col-sm-3 col-xs-6 product-item">
    <a href="index.php?q=single-item&id=<?php echo $result->PROID; ?>">
        <img class="img-hover img-related" src="<?php echo web_root . 'admin/products/' . $result->IMAGES; ?>" alt="">
    </a>
    <a href="index.php?q=single-item&id=<?php echo $result->PROID; ?>">
        <p><?php echo $result->PRODESC; ?></p>
    </a>
</div>

    <?php } ?>

  </div>

    <script>
    document.getElementById('reviewSubmitForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    var name = document.getElementById('name').value;
    var review = document.getElementById('review').value;
    var rating = document.querySelector('input[name="rating"]:checked').value;
    var proid = document.querySelector('input[name="proid"]').value;
    var csrfToken = document.querySelector('input[name="csrf_token"]').value;
    
    // Simple XSS check (this is not exhaustive)
    if (/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi.test(name) || 
        /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi.test(review)) {
        
        // Log the XSS attempt
        fetch('log_xss_attempt.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                name: name,
                review: review
            })
        }).then(response => response.json())
          .then(data => console.log('XSS attempt logged:', data))
          .catch((error) => console.error('Error logging XSS attempt:', error));

        Swal.fire({
            icon: 'error',
            title: 'Security Alert!',
            html: '<div style="font-size: 1.2em;">🚨 Potential XSS Attack Detected 🚨</div><br>' +
                  '<p>Your attempt to inject malicious code has been logged.</p>' +
                  '<p>This incident will be reported to cybersecurity authorities.</p>' +
                  '<br><div style="font-size: 1.1em; color: #ff0000;">Further attempts may result in account suspension and legal action.</div>',
            confirmButtonText: 'I Understand',
            confirmButtonColor: '#d33'
        });
    } else {
        // If no XSS detected, submit the form via AJAX
        var formData = new FormData();
        formData.append('name', name);
        formData.append('reviewtext', review);
        formData.append('rating', rating);
        formData.append('proid', proid);
        formData.append('csrf_token', csrfToken);

        fetch('submit_review.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Review Submitted!',
                    text: data.message,
                    confirmButtonColor: '#3085d6'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reload the page or update the review list
                        location.reload();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.message,
                    confirmButtonColor: '#d33'
                });
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong!',
                confirmButtonColor: '#d33'
            });
        });
    }
});
</script>
<script>
function toggleReviewForm() {
    var form = document.getElementById('reviewForm');
    var btn = document.getElementById('toggleReviewBtn');
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
        btn.textContent = 'Cancel Review';
    } else {
        hideReviewForm();
    }
}

function hideReviewForm() {
    var form = document.getElementById('reviewForm');
    var btn = document.getElementById('toggleReviewBtn');
    form.style.display = 'none';
    btn.textContent = 'Write a review';
}
</script>

<script>
function filterReviews(rating) {
    const reviews = document.querySelectorAll('.review');
    const noReviews = document.getElementById('noReviews');
    const seeFullReviews = document.getElementById('seeFullReviews');
    let visibleReviews = 0;

    reviews.forEach(review => {
        if (parseInt(review.getAttribute('data-rating')) === rating) {
            review.style.display = 'block';
            visibleReviews++;
        } else {
            review.style.display = 'none';
        }
    });

    if (visibleReviews === 0) {
        if (noReviews) {
            noReviews.style.display = 'block';
            noReviews.textContent = `No ${rating}-star reviews yet.`;
        } else {
            const reviewList = document.getElementById('reviewList');
            const noReviewsMessage = document.createElement('p');
            noReviewsMessage.id = 'noReviews';
            noReviewsMessage.textContent = `No ${rating}-star reviews yet.`;
            reviewList.appendChild(noReviewsMessage);
        }
        if (seeFullReviews) seeFullReviews.style.display = 'none';
    } else {
        if (noReviews) noReviews.style.display = 'none';
        if (seeFullReviews) seeFullReviews.style.display = 'block';
    }
}

function showAllReviews() {
    const reviews = document.querySelectorAll('.review');
    const noReviews = document.getElementById('noReviews');
    const seeFullReviews = document.getElementById('seeFullReviews');

    reviews.forEach(review => {
        review.style.display = 'block';
    });

    if (noReviews) noReviews.style.display = 'none';
    if (seeFullReviews) seeFullReviews.style.display = 'none';
}
</script>
  <script src="assets/js/scripts.js"></script>
  <script src="assets/js/bootstrap.js"></script>
