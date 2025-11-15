<?php require "includes/header.php"; ?>   
<section class="mb-4 container">

    <div class="glass-card p-5">
        <!--Section heading-->
        <h2 class="h1-responsive font-weight-bold text-center my-4 text-white">Contact us</h2>
        <!--Section description-->
        <p class="text-center w-responsive mx-auto mb-5 text-white">Do you have any questions? Please do not hesitate to contact us directly. Our team will come back to you within
            a matter of hours to help you.</p>
    
        <div class="row">
    
            <!--Grid column-->
            <div class="col-md-9 mb-md-0 mb-5">
                <form id="contact-form" name="contact-form" action="mail.php" method="POST">
    
                    <!--Grid row-->
                    <div class="row">
    
                        <!--Grid column-->
                        <div class="col-md-6">
                            <div class="md-form mb-0">
                                <label for="name" class="text-white">Your name</label>
    
                                <input type="text" id="name" name="name" class="form-control form-control-lg">
                            </div>
                        </div>
                        <!--Grid column-->
    
                        <!--Grid column-->
                        <div class="col-md-6">
                            <div class="md-form mb-0">
                                <label for="email" class="text-white">Your email</label>
    
                                <input type="text" id="email" name="email" class="form-control form-control-lg">
                            </div>
                        </div>
                        <!--Grid column-->
    
                    </div>
                    <!--Grid row-->
    
                    <!--Grid row-->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="md-form mb-0">
                                <label for="subject" class="text-white">Subject</label>
    
                                <input type="text" id="subject" name="subject" class="form-control form-control-lg">
                            </div>
                        </div>
                    </div>
                    <!--Grid row-->
    
                    <!--Grid row-->
                    <div class="row">
    
                        <!--Grid column-->
                        <div class="col-md-12">
    
                            <div class="md-form">
                                <label for="message" class="text-white">Your message</label>
    
                                <textarea type="text" id="message" name="message" rows="2" class="form-control md-textarea form-control-lg"></textarea>
                            </div>
    
                        </div>
                    </div>
                    <!--Grid row-->
    
                </form>
    
                <div class="text-center text-md-left mt-4">
                    <a class="btn btn-primary" onclick="document.getElementById('contact-form').submit();">Send</a>
                </div>
                <div class="status"></div>
            </div>
            <!--Grid column-->
    
            <!--Grid column-->
            <div class="col-md-3 text-center text-white">
                <ul class="list-unstyled mb-0">
                    <li><i class="fas fa-map-marker-alt fa-2x"></i>
                        <p>56, Hoang Dieu 2, Linh Chieu, Thu Duc, HCM, Vietnam</p>
                    </li>
    
                    <li><i class="fas fa-phone mt-4 fa-2x"></i>
                        <p>(+84) 818 093 781</p>
                    </li>
    
                    <li><i class="fas fa-envelope mt-4 fa-2x"></i>
                        <p>lucaebook.contact@gmail.com</p>
                    </li>
                </ul>
            </div>
            <!--Grid column-->
    
        </div>
    </div>

</section>
<?php require "includes/footer.php"; ?>   
