<style>
#about-store {
    background-color: #f8f8f8;
    padding: 30px 0;
    position: relative;
    overflow: hidden;
    height: 20px;
    margin-top: 100px;
}

.section-two {
    margin-top: 79px;
}

.section-three {
    margin-top: 100px;
    /* Adjusted margin-top */
}

.section-four {
    margin-top: 100px;
    /* Adjusted margin-top */
}

#about-store .triangle-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: white;
    z-index: 1;
}

#about-store .container {
    position: relative;
    z-index: 2;
}

#about-store p {
    color: #fff;
    margin-bottom: 30px;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
}

#about-store .about-content {
    font-size: 16px;
    line-height: 1.6;
    text-align: justify;
    padding: 20px;
    border-radius: 5px;
}

#about-store ul {
    margin-top: 20px;
    margin-bottom: 20px;
    padding-left: 20px;
}

#about-store li {
    margin-bottom: 10px;
}

#about-store img {
    margin-bottom: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

@media (max-width: 767px) {
    #about-store .col-sm-4 {
        text-align: center;
    }
}

#about-store .about-content,
#about-store .about-content ul,
#about-store .about-content li {
    color: #fff;

}

@media (max-width: 767px) {
    #features .card {
        margin-bottom: 20px;

    }

    #features .row {
        justify-content: center;
    }
}

.section-three .card {
    position: relative;
    overflow: hidden;
    padding-bottom: 20px;

}

.section-three .card::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 70px;

    height: 2px;

    background-color: #fd2323;
}

.img-responsive {
    margin-left: 10px;
}

.section-three .card {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease-in-out;
}

.section-three .card:hover {
    transform: translateY(-5px);
}

.section-three .card-icon {
    margin: 0 auto;
    padding: 10px;
    width: 80px;
    height: 80px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.section-three .card-icon img {
    max-width: 50px;
    max-height: 50px;
}

.section-three .card-title {
    font-size: 20px;
    margin-bottom: 15px;
}

.section-three .card-text {
    color: #696763;
    font-size: 14px;

}

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
    height: 400px; 
    display: flex;
    flex-direction: column;
    justify-content: space-between; 
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

<style>
@import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');

#click {
  display: none;
}

label[for="click"] {
  position: fixed;
  right: 30px;
  bottom: 20px;
  height: 55px;
  width: 55px;
  background: #fd2323;
  text-align: center;
  line-height: 55px;
  border-radius: 50px;
  font-size: 30px;
  color: #fff;
  cursor: pointer;
  z-index: 9999;
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
  transition: all 0.3s ease;
}

label[for="click"]:hover {
  transform: scale(1.05);
}

label[for="click"] .tooltip {
  position: absolute;
  top: -40px;
  right: 0;
  background: #333;
  color: #fff;
  padding: 5px 10px;
  border-radius: 5px;
  font-size: 14px;
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s ease;
  white-space: nowrap;
}

label[for="click"] .tooltip::after {
  content: '';
  position: absolute;
  bottom: -5px;
  right: 25px;
  width: 0;
  height: 0;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-top: 5px solid #333;
}

label[for="click"]:hover .tooltip {
  opacity: 1;
  visibility: visible;
  top: -50px;
}

label[for="click"] i {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  transition: all 0.4s ease;
}

label[for="click"] i.fas {
  opacity: 0;
  pointer-events: none;
}

#click:checked ~ label[for="click"] i.fas {
  opacity: 1;
  pointer-events: auto;
  transform: translate(-50%, -50%) rotate(180deg);
}

#click:checked ~ label[for="click"] i.fab {
  opacity: 0;
  pointer-events: none;
  transform: translate(-50%, -50%) rotate(180deg);
}

/* Adjust the wrapper position */
.wrapper {
  position: fixed;
  right: 30px;
  bottom: 90px; /* Adjusted to be just above the button */
  max-width: 400px;
  background: #fff;
  border-radius: 15px;
  box-shadow: 0px 15px 20px rgba(0,0,0,0.1);
  opacity: 0;
  pointer-events: none;
  transition: all 0.6s cubic-bezier(0.68,-0.55,0.265,1.55);
  z-index: 9998;
}

#click:checked ~ .wrapper {
  opacity: 1;
  pointer-events: auto;
}
/* .wrapper .head-text{
  line-height: 60px;
  color: #fff;
  border-radius: 15px 15px 0 0;
  padding: 0 20px;
  font-weight: 500;
  font-size: 20px;
  background: #fd2323;
} */
.wrapper .chat-box{
  padding: 20px;
  width: 100%;
}
.chat-box .desc-text{
  color: #515365;
  text-align: center;
  line-height: 25px;
  font-size: 17px;
  font-weight: 500;
}
.chat-box form{
  padding: 10px 15px;
  margin: 20px 0;
  border-radius: 25px;
  border: 1px solid lightgrey;
}
.chat-box form .field{
  height: 50px;
  width: 100%;
  margin-top: 20px;
}
.chat-box form .field:last-child{
  margin-bottom: 15px;
}
form .field input,
form .field button,
form .textarea textarea{
  width: 100%;
  height: 100%;
  padding-left: 20px;
  border: 1px solid lightgrey;
  outline: none;
  border-radius: 25px;
  font-size: 16px;
  transition: all 0.3s ease;
}
form .field input:focus,
form .textarea textarea:focus{
  border-color: #fc83bb;
}
form .field input::placeholder,
form .textarea textarea::placeholder{
  color: silver;
  transition: all 0.3s ease;
}
form .field input:focus::placeholder,
form .textarea textarea:focus::placeholder{
  color: lightgrey;
}
.chat-box form .textarea{
  height: 70px;
  width: 100%;
}
.chat-box form .textarea textarea{
  height: 100%;
  border-radius: 50px;
  resize: none;
  padding: 15px 20px;
  font-size: 16px;
}
.chat-box form .field button{
  border: none;
  outline: none;
  cursor: pointer;
  color: #fff;
  font-size: 18px;
  font-weight: 500;
  background: #fd2323;
  transition: all 0.3s ease;
}
.chat-box form .field button:active{
  transform: scale(0.97);
}

#chat-messages {
         /* border: 1px solid #ccc; */
         padding: 10px;
         height: 300px;
         overflow-y: scroll;
         background-color: white;
         max-width: 400px;
      }

      .user-message, .admin-message {
    padding: 8px;
    margin: 5px 0;
    border-radius: 5px;
    max-width: 80%;
    clear: both;
}

.user-message {
    background-color: #e0f7fa;
    float: right;
}
.admin-message {
    background-color: #f1f0f0;
    float: left;
    margin-left: 5px; /* Add some margin to separate from the admin name */
}

.admin-name {
    clear: both;
    float: left;
    margin-bottom: 5px;
    color: #888;
    font-size: 0.9em;
}

.message-content {
    margin-bottom: 5px;
}

.message-time {
    font-size: 0.8em;
    color: #777;
    display: block;
    text-align: right;
}

.message-image {
    max-width: 100%;
    max-height: 200px;
    margin-top: 5px;
    display: block;
}

      #additional-message-form {
         margin-top: 10px;
         display: none;
      }

      .message-input-wrapper {
    display: flex;
    align-items: center;
    border-radius: 20px;
    overflow: hidden;
}


#new-message {
    flex-grow: 1;
    border: none;
    padding: 10px;
    resize: none;
    height: 40px;
    font-size: 14px;
}

.send-button {
    background-color: transparent;
    border: none;
    padding: 10px;
    cursor: pointer;
    color: #fd2323;
}

.send-button:hover {
    color: #0056b3;
}

.fa-paper-plane{
    color: #fd2323;
}

.fa-paperclip{
    color: #fd2323;
}

      #additional-message-form textarea {
         width: 100%;
         padding: 10px;
         border-radius: 5px;
      }

      #additional-message-form button {
         margin-top: 5px;
         padding: 8px 16px;
         color: white;
         border: none;
         border-radius: 5px;
         cursor: pointer;
      }

      #file-upload {
         margin-top: 5px;
      }

      .file-input {
        position: relative;
    }
    .file-label {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        background-color: white;
        cursor: pointer;
    }
    .file-label i {
        margin-right: 8px;
    }

    #file-display {
    font-size: 0.9em;
    color: #666;
    padding: 5px;
    background-color: white;
    border-radius: 3px;
}

.field textarea{
    background-color:white;
}

.messge{
    background-color:white;
}

#new-message:focus {
    outline: none;
    border-color: initial;
    box-shadow: none;
}

.error-message {
    color: red;
    font-size: 0.8em;
    margin-top: 5px;
    display: none;
}

.input-requirements {
    font-size: 0.9em;
    color: #666;
    margin-top: 10px;
}

.error {
    border-color: red;
}
   
   
</style>
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<section id="slider">
    <!--slider-->
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div id="slider-carousel" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#slider-carousel" data-slide-to="0" class="active"></li>
                        <li data-target="#slider-carousel" data-slide-to="1"></li>
                        <li data-target="#slider-carousel" data-slide-to="2"></li>
                    </ol>

                    <div class="carousel-inner">
                        <div class="item active">
                            <div class="col-sm-6">
                                <h1><span style="color:#fd2323;">Windale Hardware</span> Store</h1>
                                <h2>Buy Now!</h2>
                                <p>Welcome to Windale Hardware, where quality meets craftsmanship! Whether you're a
                                    seasoned DIY enthusiast or just starting out on your home improvement journey, we're
                                    thrilled to have you here. Explore our extensive collection of tools, hardware, and
                                    accessories designed to tackle any project with ease. Welcome aboard! </p>

                            </div>
                            <div class="col-sm-6">
                                <img src="img/windalestore.jpg" class="girl img-responsive" alt="" />
                            </div>
                        </div>
                        <div class="item">
                            <div class="col-sm-6">
                                <h1><span style="color:#fd2323;">Welcome</span></h1>
                                <h2> Windale Hardware</h2>
                                <p>Where we offer a wide range of high-quality products
                                    designed to meet your DIY needs with durability and reliability.</p>

                            </div>
                            <div class="col-sm-6">
                                <img src="img/Winsdale.jpg" class=" img-responsive" alt="" />
                            </div>
                        </div>

                    </div>

                    <a href="#slider-carousel" class="left control-carousel hidden-xs" data-slide="prev">
                        <i class="fa fa-angle-left"></i>
                    </a>
                    <a href="#slider-carousel" class="right control-carousel hidden-xs" data-slide="next">
                        <i class="fa fa-angle-right"></i>
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>
<!--/slider-->

<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <?php include 'sidebar.php'; ?>
            </div>

            <div class="col-sm-9 padding-right">
                <div class="features_items">
                    <!--features_items-->
                    <h2 class="title text-center">Features Items</h2>

                    <?php
$query = "SELECT p.*, pr.*, c.*, 
(SELECT AVG(RATING) FROM tblcustomerreview WHERE PROID = p.PROID) as avg_rating,
(SELECT COUNT(*) FROM tblcustomerreview WHERE PROID = p.PROID) as review_count
FROM `tblpromopro` pr 
JOIN `tblproduct` p ON pr.`PROID` = p.`PROID` 
JOIN `tblcategory` c ON p.`CATEGID` = c.`CATEGID`  
WHERE PROQTY > 0";
$mydb->setQuery($query);
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
                    
                    <!-- Updated Star Rating Display -->
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
                    
                    <button type="submit" name="btnorder" class="btn btn-default add-to-cart">
                        <i class="fa fa-shopping-cart"></i>Add to cart
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
<?php } ?>

                </div>
                <!--features_items-->

                <!--  -->
                <!--/recommended_items-->

            </div>
        </div>
    </div>
</section>

<section id="features" class="section-three" data-aos="fade-right">
    <div class="triangle-background"></div>
    <div class="container">
        <div class="row">
            <div class="col-sm-4 col-xs-12" data-aos="fade-right" data-aos-delay="100">
                <div class="card text-center">
                    <div class="card-icon">
                        <img src="img/product-range.png" alt="Feature 1 Icon" class="img-icon" width="80" height="70">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Wide Range of Products</h5>
                        <p class="card-text">Explore our extensive collection of tools, hardware, and accessories designed to tackle any project with ease.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12" data-aos="fade-up" data-aos-delay="200">
                <div class="card text-center">
                    <div class="card-icon">
                        <img src="img/quality-control.png" alt="Feature 2 Icon" class="img-icon" width="80" height="70">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Quality and Durability</h5>
                        <p class="card-text">We pride ourselves on offering high-quality products that are durable and reliable, ensuring your projects are built to last.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12" data-aos="fade-left" data-aos-delay="300">
                <div class="card text-center">
                    <div class="card-icon">
                        <img src="img/influencer.png" alt="Feature 3 Icon" class="img-icon" width="80" height="70">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Expert Customer Support</h5>
                        <p class="card-text">Our knowledgeable staff are here to assist you, whether you're a professional contractor or a DIY enthusiast, ensuring you have the support you need.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="quote-section" class="section-four" data-aos="fade-up-left">
    <div class="container ">
        <div class="row justify-content-center">
            <div class="col-sm-5" data-aos="fade-right" data-aos-delay="100">
                <img src="img/group.png" class="img-responsive" alt="Quote Image" width="500" height="450">
            </div>
            <div class="col-sm-7" data-aos="fade-left" data-aos-delay="200">
                <div class="quote-message">
                    <blockquote>
                        <p style="text-align:justify;"> Welcome to Windale Hardware, your one-stop shop for all your home improvement needs. With years of experience in the industry, we pride ourselves on offering quality tools, hardware, and expert advice to both professionals and DIY enthusiasts alike.</p>
                    </blockquote>
                    <em class="author">- Windale Hardware Team</em>
                </div>
            </div>
        </div>
    </div>
</section>



<section id="about-store" class="section-three">
    <div class="triangle-background"></div>
    <div class="container">
        <p class=" text-center" style="color:black;">&copy; Windale Hardware Inc. All right reserved</p>
    <!-- <div class="row">
            <div class="col-sm-4">
                <img src="img/windalestore.jpg" alt="Windale Hardware Store Front" class="img-responsive">
            </div>
           <div class="col-sm-8">
                <div class="about-content">
                    <h4><span style=font-size:16px;>Windale Hardware </span> is your one-stop shop for all your home
                        improvement needs. With a
                        couple years
                        of
                        experience in the industry, we pride ourselves on offering good tools, hardware, and
                        to both professional contractors and DIY enthusiasts.</h4>
                </div>


            </div> 
        </div> 
    </div> -->
</section>

<input type="checkbox" id="click">
      <label for="click">
      <i class="fab fa-weixin"></i>
      <i class="fas fa-times"></i>
      <span class="tooltip">Chat with us</span>
      </label>
      <div class="wrapper">
         <!-- <div class="head-text">
         Need help? Let's chat!
         </div> -->
         <div class="chat-box">
            <div class="desc-text">
            Need Help? Let's Chats!
            </div>
         <form id="chat-form" enctype="multipart/form-data">
               <div class="field">
                  <input type="text" id="name" placeholder="Your Name" required>
                  <div id="name-error" class="error-message"></div>
               </div>
               <div class="field textarea">
                  <textarea id="message" cols="30" rows="10" placeholder="What's your concern?" required></textarea>
                  <div id="message-error" class="error-message"></div>
               </div>
               <div class="field file-input">
        <input type="file" id="file-upload" name="image" accept="image/jpeg,image/png,image/jpg" style="display: none;">
        <label for="file-upload" class="file-label">
            <i class="fas fa-paperclip"></i>
            <span>Choose a file</span>
        </label>
    </div>
               <div class="field">
                  <button type="submit">Start Chat</button>
               </div>
            </form>

            <div id="chat-messages" style="display:none;"></div>

            <form id="additional-message-form" enctype="multipart/form-data">
    <div class="message-input-wrapper">
    <label for="additional-file-upload" class="file-label">
                <i class="fas fa-paperclip"></i>
            </label>
            <input type="file" id="additional-file-upload" name="image" accept="image/jpeg,image/png,image/jpg" style="display: none;">
        <textarea id="new-message" class="messge" placeholder="Type your message..." required></textarea>
        <div class="form-actions">   
            <button class="send-button" type="submit">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
    <div id="file-display" style="display: none; margin-top: 5px;"></div>
</form>
         </div>
      </div>
      <script>document.getElementById('file-upload').addEventListener('change', function(event) {
    const fileName = event.target.files[0]?.name;
    if (fileName) {
        document.querySelector('.file-label span').textContent = fileName;
    } else {
        document.querySelector('.file-label span').textContent = 'Choose a file';
    }
});

document.getElementById('additional-file-upload').addEventListener('change', function(event) {
    const fileName = event.target.files[0]?.name;
    const fileDisplay = document.getElementById('file-display');
    
    if (fileName) {
        fileDisplay.textContent = `Selected file: ${fileName}`;
        fileDisplay.style.display = 'block';
    } else {
        fileDisplay.style.display = 'none';
    }
});

document.getElementById('additional-message-form').addEventListener('submit', function(event) {
    event.preventDefault(); 

    document.getElementById('file-display').textContent = ''; 
});

</script>
<script>
             const form = document.getElementById('chat-form');
const additionalMessageForm = document.getElementById('additional-message-form');
const messagesDiv = document.getElementById('chat-messages');
const descText = document.querySelector('.desc-text');

let sessionId = '';
let userName = '';
// let displayedMessages = new Set();
let lastMessageTimestamp = null;
const displayedMessages = new Set();

function showChatInterface() {
    form.style.display = 'none';
    descText.style.display = 'none';
    messagesDiv.style.display = 'block';
    additionalMessageForm.style.display = 'block';
}
// Call this function when the page loads to check for an existing session
function checkExistingSession() {
    const storedSessionId = localStorage.getItem('chatSessionId');
    const storedUserName = localStorage.getItem('chatUserName');
    
    if (storedSessionId && storedUserName) {
        sessionId = storedSessionId;
        userName = storedUserName;
        showChatInterface();
        getMessages();
    }
}

// Call this function at the end of your script


function containsXSS(input) {
    const xssPattern = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>|on\w+\s*=|javascript:|<\s*\/?[a-z]|&#|%3C/i;
    return xssPattern.test(input);
}

function showError(inputElement, message) {
    const errorElement = inputElement.nextElementSibling;
    if (errorElement && errorElement.classList.contains('error-message')) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }
}

// Function to clear error message
function clearError(inputElement) {
    const errorElement = inputElement.nextElementSibling;
    if (errorElement && errorElement.classList.contains('error-message')) {
        errorElement.textContent = '';
        errorElement.style.display = 'none';
    }
}

// Modify the initial chat form submission
form.addEventListener('submit', function(event) {
    event.preventDefault();
    const nameInput = document.getElementById('name');
    const messageInput = document.getElementById('message');
    const fileInput = document.getElementById('file-upload');

    let isValid = true;

    if (containsXSS(nameInput.value)) {
        showError(nameInput, "Invalid input. Please remove any special characters.");
        isValid = false;
    } else {
        clearError(nameInput);
    }

    if (containsXSS(messageInput.value)) {
        showError(messageInput, "Invalid input. Please remove any special characters.");
        isValid = false;
    } else {
        clearError(messageInput);
    }

    if (!isValid) return;

    userName = nameInput.value;

    fetch(`checkExistingConversation.php?name=${encodeURIComponent(userName)}`)
    .then(response => response.json())
    .then(data => {
        const formData = new FormData();
        formData.append('name', userName);
        formData.append('message', messageInput.value);
        
        if (fileInput.files.length > 0) {
            formData.append('image', fileInput.files[0]);
        }

        if (data.exists) {
            sessionId = data.sessionId;
            formData.append('sessionId', sessionId);
        }

        return fetch('sendMessage.php', {
            method: 'POST',
            body: formData
        });
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            sessionId = data.sessionId;
            showChatInterface();
            getMessages();
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});

function displayMessage(sender, message, time, type, imagePath) {
    const msgDiv = document.createElement('div');
    msgDiv.className = type === 'admin' ? 'admin-message' : 'user-message';

    // Create admin name div BEFORE the message div, and append it directly to messagesDiv
    if (type === 'admin') {
        const nameDiv = document.createElement('div');
        nameDiv.className = 'admin-name';
        nameDiv.textContent = sender;
        messagesDiv.appendChild(nameDiv);  // Append directly to messagesDiv first
    }

    const contentDiv = document.createElement('div');
    contentDiv.className = 'message-content';
    contentDiv.textContent = message;
    msgDiv.appendChild(contentDiv);

    const timeSpan = document.createElement('span');
    timeSpan.className = 'message-time';
    timeSpan.textContent = time;
    msgDiv.appendChild(timeSpan);

    if (imagePath) {
        const img = document.createElement('img');
        img.src = imagePath;
        img.className = 'message-image';
        img.onerror = () => console.error('Failed to load image:', imagePath);
        msgDiv.appendChild(img);
    }

    messagesDiv.appendChild(msgDiv);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}

additionalMessageForm.addEventListener('submit', function(event) {
    event.preventDefault();

    const newMessageInput = document.getElementById('new-message');
    const fileInput = document.getElementById('additional-file-upload');

    if (containsXSS(newMessageInput.value)) {
        showError(newMessageInput, "Invalid input. Please remove any special characters.");
        return;
    } else {
        clearError(newMessageInput);
    }

    if (!newMessageInput.value.trim()) {
        showError(newMessageInput, "Please enter a message.");
        return;
    }

    const formData = new FormData();
    formData.append('sessionId', sessionId);
    formData.append('message', newMessageInput.value);
    if (fileInput.files[0]) {
        formData.append('image', fileInput.files[0]);
    }

    fetch('sendMessage.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            newMessageInput.value = '';
            fileInput.value = '';
            getMessages();
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});

function getMessages() {
    if (!sessionId) return;

    fetch(`getMessages.php?sessionId=${sessionId}`)
        .then(response => response.json())
        .then(data => {
            console.log('Received messages:', data);

            // Sort messages by timestamp
            data.sort((a, b) => new Date(a.timestamp) - new Date(b.timestamp));

            // Clear messages only if we have new data
            if (data.length > 0) {
                messagesDiv.innerHTML = '';
                displayedMessages.clear();
            }

            // Track unique messages using message ID and timestamp
            data.forEach(msg => {
                const messageKey = `${msg.id}_${msg.timestamp}`;
                
                // Only display message if it hasn't been shown before
                if (!displayedMessages.has(messageKey)) {
                    const msgDate = new Date(msg.timestamp);
                    msgDate.setHours(msgDate.getHours() + 8); // Adjust to Philippine time
                    
                    const time = msgDate.toLocaleTimeString('en-PH', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: true
                    });

                    displayMessage(msg.sender, msg.message, time, msg.type, msg.image_path);
                    displayedMessages.add(messageKey);
                }
            });
        })
        .catch(error => console.error('Error:', error));
}

const messagePolling = setInterval(getMessages, 5000);

// Clean up polling when needed
function cleanupChat() {
    clearInterval(messagePolling);
}
            checkExistingSession();
         </script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1600, // Animation duration in milliseconds
            once: true, // Whether animation should happen only once - while scrolling down
            mirror: false, // Whether elements should animate out while scrolling past them
        });
    </script>