<?php
/**
 * The template used for displaying single page content in footer.php
 *
 * @package iknowledgebase
 */
?>


<div class="footer-subscribe-social">

    <div class="block newsletter">
    
        <div class="content">
            <div class="md-text text-center">
                <p class="news-letter-title">MowNews</p>
                <p>Sign up for tips, reviews &amp; special offers</p>
            </div>
            <form class="form subscribe" novalidate="novalidate" action="https://www.mowdirect.co.uk/newsletter/subscriber/new/" method="post" id="newsletter-validate-detail">
                <div class="field newsletter">
                    <div class="control">
                        <label for="newsletter">
                        <input name="email" type="email" id="newsletter" placeholder="Enter your email address" data-validate="{required:true, 'validate-email':true}">
                        </label>
                    </div>
                </div>
                <div class="actions">
                    <button class="action subscribe primary" title="Register" type="submit" aria-label="Register">
                    <span>Register</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>