<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
    function onSubmit(token) {
        document.getElementById("newsletter").submit();
    }
</script>
<div class="md-text">
    <h4>MowNews</h4>
    <p>Sign Up for Tips, Reviews &amp; Special Offers</p>
</div>
<form id="newsletter_subscribe_form" action="https://www.mowdirect.co.uk/newsletter/subscriber/new/" method="post" class="clearfix">
    <input name="email"
           id="newsletter"
           value="Enter your email"
           onblur="if(this.value==''){this.value='Enter your email'}"
           onfocus="if(this.value=='Enter your email'){this.value=''}"
           class="input-text required-entry"
           type="text">
    <input
            id="newsletter_subscribe_submit"
            type="image"
            class="g-recaptcha"
            data-sitekey="6Lcr1T8UAAAAADlbmIlDjUJ_bemjM2JvjktoPbbY"
            data-callback='onSubmit'
            src='<?php  echo get_stylesheet_directory_uri() ."/assets/img/newsletter-submit.png" ?>'>
</form>
<div class="overlay"></div>
<style>
    form#newsletter_subscribe_form {
        position: relative;
    }
    form#newsletter_subscribe_form input#newsletter {
        float: left;
        width: 100%;
        border-radius: 20px;
        color:#000000;
        padding: 3px 7px;
        height: 40px;
    }
    #newsletter_subscribe_submit {
        background: none;
        border: none;
        height: 38px;
        padding: 0;
        position: absolute;
        right: 1px;
        top: 1px;
        width: 40px;
        cursor: pointer;
    }
</style>