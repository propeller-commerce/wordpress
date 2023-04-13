<?php

use Propeller\Includes\Controller\PageController;
use Propeller\Includes\Enum\PageType;
use Propeller\PropellerHelper;
    $pay_methods = $this->cart->payMethods;
    $delivery_address = $this->get_delivery_address();
    $invoice_address = $this->get_invoice_address();
?>
<svg style="display: none;">
    <symbol viewBox="0 0 18 21" id="shape-checkout-edit"><title>Edit</title> <g fill="none" fill-rule="evenodd"><path d="M17.34 1.978 16.023.659A2.242 2.242 0 0 0 14.432 0c-.577 0-1.152.22-1.592.659L.452 13.047l-.447 4.016a.844.844 0 0 0 .932.932l4.012-.444L17.341 5.16a2.25 2.25 0 0 0 0-3.182zM4.434 16.477l-3.27.362.363-3.275 9.278-9.277 2.91 2.91-9.281 9.28zM16.546 4.364l-2.037 2.037-2.91-2.91 2.037-2.037c.212-.212.495-.329.795-.329.3 0 .583.117.796.33l1.319 1.318a1.127 1.127 0 0 1 0 1.591z" fill="#005FAD" fill-rule="nonzero"/><path stroke="#005FAD" stroke-linecap="round" d="M.5 20.5h17"/></g></symbol>  
    <symbol viewBox="0 0 38 33" id="shape-Ideal"><title>Ideal</title> <g transform="translate(2.111 2.063)" fill="none"><path d="M17.4 28.36H2.865c-1.29 0-2.337-1.028-2.337-2.294V2.809C.528 1.543 1.575.516 2.865.516H17.4c13.793 0 15.85 8.708 15.85 13.892 0 8.994-5.636 13.951-15.85 13.951z" fill="#FFF"/><path d="M9.198 4.707v21.034h9.287c8.432 0 12.088-4.695 12.088-11.334 0-6.355-3.656-11.285-12.088-11.285h-7.678a1.6 1.6 0 0 0-1.609 1.585z" fill="#C06"/><path d="M17.417 28.875H2.413C1.08 28.875 0 27.81 0 26.497V2.378C0 1.065 1.08 0 2.413 0h15.004c14.237 0 16.36 9.03 16.36 14.407 0 9.327-5.817 14.468-16.36 14.468zM2.413.793C1.52.793.804 1.499.804 2.378v24.119c0 .88.717 1.585 1.609 1.585h15.004c10.027 0 15.557-4.856 15.557-13.675C32.974 2.564 23.222.793 17.417.793H2.413z" fill="#000"/><path d="M12.76 11.822c.326 0 .628.05.917.149.289.099.527.26.74.458.202.21.365.47.491.768.113.31.176.669.176 1.09 0 .372-.05.706-.138 1.016-.1.31-.239.582-.428.805a2.073 2.073 0 0 1-.716.533 2.565 2.565 0 0 1-1.018.198h-2.199v-5.03h2.174v.013zm-.076 4.1c.163 0 .314-.024.477-.074a.938.938 0 0 0 .402-.26 1.44 1.44 0 0 0 .29-.47c.075-.2.113-.422.113-.707a2.83 2.83 0 0 0-.076-.681 1.346 1.346 0 0 0-.251-.52 1.132 1.132 0 0 0-.453-.335 1.874 1.874 0 0 0-.69-.111h-.805v3.17h.993v-.012zm6.936-4.1v.93h-2.689v1.077h2.476v.855H16.93v1.226h2.752v.929h-3.87v-5.03h3.807zm3.846 0 1.91 5.03h-1.169l-.39-1.116h-1.91l-.402 1.115h-1.13l1.922-5.029h1.169zm.062 3.085-.64-1.846h-.013l-.666 1.846h1.32zm3.67-3.085v4.1h2.488v.93h-3.607v-5.03h1.119z" fill="#FFF"/><ellipse fill="#000" cx="5.295" cy="14.337" rx="2.35" ry="2.316"/><path d="M7.067 25.993c-1.973 0-3.556-1.573-3.556-3.505V19.75a1.77 1.77 0 0 1 1.784-1.759c.98 0 1.784.78 1.784 1.759v6.243h-.012z" fill="#000"/></g></symbol>  
    <symbol viewBox="0 0 76 18" id="shape-PayPal"><title>Paypal</title> <g fill="none"><path d="M9.868 0c.257 0 .513.125.898.125 1.153.25 2.307.625 2.947 1.75.385.625.513 1.375.385 2.125v.625L13.97 4.5c-.77-.375-1.666-.5-2.435-.5h-4.87a.708.708 0 0 0-.641.375c-.129.125-.129.375-.129.5-.128.75-.256 1.375-.384 2.125-.128.625-.256 1.25-.256 1.875-.129.5-.129 1.125-.257 1.625v.25c-.128.625-.256 1.25-.256 1.875-.128.75-.256 1.625-.384 2.375-.129.375-.129.75-.257 1.125H.641A.708.708 0 0 1 0 15.75v-.5c0-.125.128-.375.128-.5.128-1 .385-2 .513-2.875l.384-2.625.385-2.625L1.794 4c.128-.875.257-1.75.385-2.75.128-.5.128-.875.64-1.125C5.127 0 7.434 0 9.87 0z" fill="#013088"/><path d="M13.97 4.625s0-.125 0 0c.897.375 1.41 1.125 1.538 2.125.128 1.75-.257 3.375-1.538 4.75-.77.75-1.666 1.25-2.692 1.375-.64.125-1.41.125-2.05.125-.513 0-.77.25-.897.75-.257 1.125-.385 2.375-.513 3.5 0 .375-.256.625-.513.75H3.973c-.256-.125-.256-.375-.256-.625.128-.5.128-.875.256-1.375.128-.375.128-.75.256-1.125.129-.75.257-1.625.385-2.375.128-.625.256-1.25.256-1.875v-.25c.256-.375.641-.5 1.025-.375h.385c.897 0 1.922 0 2.82-.125 2.82-.625 4.357-2.5 4.87-5.25z" fill="#019BDD"/><path d="M42.806 18c-.256-.25-.256-.375 0-.625.64-.875 1.282-1.75 1.794-2.5.128-.125.128-.25 0-.5-.64-2-1.41-4-2.05-6V8.25c-.128-.25 0-.5.384-.5h1.923c.256 0 .512.125.64.5.385 1.25.77 2.375 1.154 3.625V12l.769-1.125C48.06 10 48.702 9 49.342 8.125a.708.708 0 0 1 .641-.375h1.794c.129 0 .385 0 .385.25.128.125 0 .25-.128.375-2.179 3.125-4.358 6.25-6.665 9.375-.128.125-.256.125-.384.25h-2.179z" fill="#023188"/><path d="M76 4.625c-.128.375-.128.75-.256 1.25-.385 2-.641 4-1.026 6.125-.128 1-.256 2-.512 2.875 0 .375-.257.625-.641.625h-1.538c-.385 0-.513-.125-.513-.5.128-1.125.385-2.375.513-3.5.256-1.875.64-3.625.897-5.5.128-.5.128-1 .256-1.5 0-.375.129-.375.513-.375h1.666c.257 0 .385 0 .513.25.128-.125.128.125.128.25z" fill="#029CDD"/><path d="M27.042 11.5h-.897c-.384 0-.513.25-.64.5-.129.875-.257 1.75-.385 2.75 0 .375-.257.5-.641.5h-1.923c-.384 0-.512-.125-.384-.5.384-2.25.769-4.625 1.153-6.875.129-1.125.385-2.25.513-3.375.128-.375.256-.625.64-.625h4.358c.77 0 1.41.125 2.051.5.769.375 1.282 1.125 1.282 2 .128 1.5-.257 2.75-1.282 3.875-.64.625-1.41 1-2.307 1.125-.384.125-.897.125-1.538.125 0 .125 0 0 0 0zm-1.025-2.25h1.538c.64 0 1.153-.25 1.41-.875.128-.25.128-.625.128-1 0-.5-.257-.875-.897-1-.513-.125-1.026-.125-1.538-.125-.128 0-.257.125-.385.25 0 1-.128 1.875-.256 2.75z" fill="#023188"/><path d="M56.648 4h2.563c.512 0 1.025.125 1.538.375.897.375 1.41 1.125 1.538 2.125.128 1-.128 1.875-.513 2.75-.64 1.375-1.794 2.125-3.332 2.25-.641.125-1.41 0-2.179 0-.513 0-.64.125-.769.625-.128.875-.256 1.875-.513 2.75 0 .25-.128.375-.384.375h-2.179c-.256 0-.384-.125-.384-.5.512-2.875.897-5.75 1.41-8.625.128-.625.128-1.125.256-1.75.128-.375.256-.625.64-.625.77.25 1.539.25 2.308.25zm-.77 5.25c.641 0 1.154 0 1.667-.125.897-.125 1.281-.5 1.41-1.25V7.5c0-.625-.257-1.125-.898-1.25-.512-.125-1.025-.125-1.41-.125-.128 0-.256.125-.256.375-.128.5-.128 1-.256 1.5s-.128.875-.256 1.25z" fill="#029CDD"/><path d="M38.705 8.5c0-.125 0-.25.128-.375 0-.25.128-.375.513-.375h1.794c.384 0 .513.125.513.5-.129.75-.257 1.625-.385 2.375-.256 1.375-.384 2.75-.64 4.125 0 .375-.257.5-.641.5H38.32c-.384 0-.512-.125-.384-.5v-.375c-.513.375-1.025.75-1.538.875-1.153.25-2.179.25-3.204-.375-.769-.5-1.154-1.25-1.282-2.125-.256-1.875.641-3.75 2.307-4.75 1.154-.625 2.435-.75 3.717-.375.128.25.513.5.769.875-.128 0 0 0 0 0zm-2.563 4.875c1.153 0 2.178-.875 2.307-2 .128-.875-.385-1.5-1.282-1.75-1.282-.25-2.307.5-2.691 1.625-.257 1.25.384 2.25 1.666 2.125z" fill="#023188"/><path d="M68.567 8.5c0-.125 0-.375.128-.5 0-.25.128-.375.384-.375h1.923c.256 0 .384.125.384.5-.128.75-.256 1.625-.384 2.375-.257 1.375-.385 2.75-.641 4.125-.128.5-.256.625-.77.625h-1.537c-.385 0-.513-.125-.385-.5v-.375c-.384.25-.64.5-1.025.625-.897.5-1.922.5-2.948.25-1.153-.375-1.794-1.25-2.05-2.375C61.26 10.625 62.67 8 65.363 7.5c.769-.125 1.538-.125 2.178.125.385.25.77.5 1.026.875zm-.257 2.75c0-1-.64-1.625-1.794-1.5-1.282.125-2.05.875-2.179 2-.128.75.385 1.375 1.026 1.625 1.41.375 2.947-.625 2.947-2.125z" fill="#029CDD"/><path d="M13.97 4.625c-.385 2.75-1.923 4.75-4.87 5.25C8.201 10 7.176 10 6.28 10h-.385c-.384 0-.769.125-1.025.375.128-.5.128-1.125.256-1.625.129-.625.257-1.25.257-1.875.128-.75.256-1.375.384-2.125 0-.125.128-.375.128-.5a.708.708 0 0 1 .641-.375h4.87c.77.125 1.667.25 2.564.75-.129-.125-.129-.125 0 0z" fill="#012269"/></g></symbol>  
    <symbol viewBox="0 0 58 19" id="shape-Visa"><title>Visa</title><defs><path id="a" d="M0 0h14.094v18.77H0z"/><path id="c" d="M0 0h11.407v9.879H0z"/></defs><g fill="none" fill-rule="evenodd"><path fill="#0066B3" d="m23.374.33-2.94 18.17h4.7L28.072.33z"/><g transform="translate(28.071)"><mask id="b" fill="#fff"><use xlink:href="#a"/></mask><path d="M14.094.774C13.167.406 11.697 0 9.88 0 5.24 0 1.973 2.475 1.953 6.012c-.039 2.61 2.339 4.059 4.117 4.93 1.818.889 2.436 1.469 2.436 2.262-.018 1.217-1.469 1.778-2.821 1.778-1.876 0-2.881-.29-4.409-.966l-.618-.291L0 17.804c1.102.502 3.132.948 5.24.967 4.93 0 8.139-2.436 8.178-6.205.018-2.07-1.237-3.654-3.944-4.95-1.644-.83-2.65-1.391-2.65-2.241.019-.774.851-1.566 2.706-1.566 1.528-.04 2.65.328 3.5.696l.425.193.639-3.924z" fill="#0066B3" mask="url(#b)"/></g><path d="M54.21.33h-3.634c-1.121 0-1.972.327-2.456 1.507L41.14 18.5h4.93l.987-2.726h6.033c.134.638.56 2.726.56 2.726H58L54.21.33zm-5.8 11.733c.387-1.044 1.876-5.084 1.876-5.084-.02.038.386-1.064.618-1.74l.329 1.566s.889 4.35 1.083 5.258H48.41zM16.51.33l-4.6 12.39-.503-2.513c-.851-2.9-3.52-6.05-6.496-7.617l4.215 15.891h4.968L21.479.33H16.51z" fill="#0066B3"/><g transform="translate(0 .33)"><mask id="d" fill="#fff"><use xlink:href="#c"/></mask><path d="M7.637 0H.077L0 .367C5.897 1.875 9.802 5.51 11.407 9.879L9.764 1.527C9.493.367 8.662.038 7.637 0" fill="#FAA61A" mask="url(#d)"/></g></g> </symbol>  
    <symbol viewBox="0 0 42 33" id="shape-Mastercard"><title>Mastercard</title> <<defs><path id="a" d="M0 0h37.885v4.906H0z"/><path id="c" d="M0 32.618h42V0H0z"/></defs><g fill="none" fill-rule="evenodd"><g transform="translate(2.093 27.713)"><mask id="b" fill="#fff"><use xlink:href="#a"/></mask><path d="M6.965 3.102c0-.614.395-1.118 1.047-1.118.614 0 1.046.471 1.046 1.118 0 .614-.432 1.118-1.046 1.118-.652-.037-1.047-.508-1.047-1.118zm2.812 0V1.37H9.02v.434c-.252-.324-.614-.505-1.085-.505-.975 0-1.731.757-1.731 1.803 0 1.047.756 1.804 1.73 1.804.506 0 .867-.182 1.086-.505v.434h.756V3.102zm25.33 0c0-.614.394-1.118 1.046-1.118.614 0 1.047.471 1.047 1.118 0 .614-.433 1.118-1.047 1.118-.652-.037-1.047-.508-1.047-1.118zm2.778 0V0h-.757v1.804c-.252-.324-.613-.505-1.084-.505-.975 0-1.732.757-1.732 1.803 0 1.047.757 1.804 1.732 1.804.504 0 .866-.182 1.084-.505v.434h.757V3.102zM19.087 1.947c.471 0 .795.29.866.827h-1.802c.071-.466.395-.827.936-.827zm0-.652c-1.008 0-1.73.723-1.73 1.803 0 1.085.722 1.804 1.769 1.804.504 0 1.008-.144 1.407-.472l-.36-.542c-.291.219-.652.362-1.01.362-.47 0-.937-.22-1.046-.829h2.56v-.29c.034-1.113-.618-1.836-1.59-1.836zm9.055 1.807c0-.614.395-1.118 1.047-1.118.613 0 1.046.471 1.046 1.118 0 .614-.433 1.118-1.046 1.118-.648-.037-1.047-.508-1.047-1.118zm2.778 0V1.37h-.756v.434c-.253-.324-.614-.505-1.085-.505-.975 0-1.731.757-1.731 1.803 0 1.047.756 1.804 1.73 1.804.506 0 .867-.182 1.086-.505v.434h.756V3.102zm-6.998 0c0 1.047.723 1.804 1.841 1.804.504 0 .865-.11 1.227-.396l-.362-.613c-.29.218-.576.323-.904.323-.613 0-1.046-.432-1.046-1.118 0-.651.433-1.084 1.046-1.118.324 0 .615.11.904.324l.362-.613c-.362-.29-.723-.396-1.227-.396-1.118-.004-1.841.757-1.841 1.803zm9.668-1.807c-.434 0-.723.218-.905.504v-.432h-.756V4.83h.756V2.88c0-.577.253-.904.723-.904a2.3 2.3 0 0 1 .471.072l.22-.715c-.148-.038-.363-.038-.51-.038zm-20.13.361c-.362-.252-.867-.36-1.408-.36-.866 0-1.443.432-1.443 1.117 0 .576.434.904 1.19 1.008l.362.039c.395.071.613.18.613.36 0 .253-.29.434-.794.434a1.99 1.99 0 0 1-1.156-.362l-.361.576c.395.29.937.434 1.479.434 1.009 0 1.589-.472 1.589-1.119 0-.614-.471-.937-1.19-1.046l-.36-.038c-.324-.038-.577-.11-.577-.324 0-.252.253-.395.651-.395.434 0 .866.181 1.085.29l.32-.614zm9.705-.36c-.433 0-.723.217-.904.503v-.432h-.756V4.83h.756V2.88c0-.577.252-.904.723-.904a2.3 2.3 0 0 1 .471.072l.218-.723c-.15-.03-.365-.03-.508-.03zm-6.423.074h-1.227V.324h-.757V1.37h-.685v.686h.685v1.589c0 .795.323 1.261 1.19 1.261.323 0 .685-.11.937-.253L16.666 4a1.21 1.21 0 0 1-.652.182c-.36 0-.504-.22-.504-.577v-1.55h1.228V1.37h.004zM5.52 4.835V2.67c0-.829-.504-1.371-1.37-1.371-.433 0-.904.143-1.227.614-.252-.395-.614-.614-1.157-.614-.36 0-.722.11-1.008.505V1.37H0v3.465h.757V2.922c0-.614.324-.904.828-.904.505 0 .757.324.757.904v1.913h.756V2.922c0-.614.362-.904.828-.904.504 0 .756.324.756.904v1.913h.837z" fill="#000" mask="url(#b)"/></g><mask id="d" fill="#fff"><use xlink:href="#c"/></mask><path fill="#FF5A00" mask="url(#d)" d="M15.3 23.199h11.367V2.779H15.3z"/><path d="M16.057 12.988c0-4.148 1.95-7.83 4.943-10.21A12.924 12.924 0 0 0 12.99 0C5.809 0 0 5.81 0 12.988c0 7.18 5.809 12.989 12.989 12.989 3.03 0 5.808-1.047 8.011-2.78a12.958 12.958 0 0 1-4.943-10.209" fill="#EB001B" mask="url(#d)"/><path d="M42 12.988c0 7.18-5.808 12.989-12.988 12.989-3.031 0-5.81-1.047-8.012-2.78a12.915 12.915 0 0 0 4.944-10.209c0-4.148-1.95-7.83-4.944-10.21A12.901 12.901 0 0 1 29.007 0C36.192 0 42 5.847 42 12.988" fill="#F79E1B" mask="url(#d)"/></g>/symbol>  
   
</svg>
<div class="propeller-checkout-wrapper">
    <div class="container-fluid px-0 checkout-header-wrapper">
        <div class="row align-items-start">
            <div class="col-12 col-sm mr-auto checkout-header">
                <h1><?php echo __('Order', 'propeller-ecommerce'); ?></h1>
            </div>
        </div>
    </div>
    <div class="container-fluid px-0">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="checkout-wrapper-steps">
                    <div class="row align-items-start">
                        <div class="col-10 col-md-3 col-lg-3">
                            <div class="checkout-step"><?php echo __('Step 1', 'propeller-ecommerce'); ?></div>
                            <div class="checkout-title"><?php echo __('Your details', 'propeller-ecommerce'); ?></div>
                        </div>
                        <div class="col-12 col-md-7 col-lg-7 ml-md-auto order-3 order-md-2 user-details">
                            <div class="user-fullname">
                                <?php if ($invoice_address->gender === 'M') {
                                        echo SALUTATION_M;
                                    }
                                    else if ($invoice_address->gender === 'F') {
                                        echo SALUTATION_F;
                                    }
                                    else {
                                        echo SALUTATION_U;
                                    }
                                        
                                ?> <?php echo esc_html($invoice_address->firstName); ?> <?php echo esc_html($invoice_address->lastName); ?>
                            </div>
                            <div class="user-addr-details">
                                <?php echo esc_html($invoice_address->company); ?><br>
                                <?php echo esc_html($invoice_address->street); ?> <?php echo esc_html($invoice_address->number); ?> <?php echo esc_html($invoice_address->numberExtension); ?><br>
                                <?php echo esc_html($invoice_address->postalCode); ?> <?php echo esc_html($invoice_address->city); ?><br>
                                <?php 
                                    $code = $invoice_address->country;
                                    $countries = include PROPELLER_PLUGIN_DIR . '/includes/Countries.php'; 

                                    if( !$countries[$code] ) 
                                        echo esc_html($code);
                                    else 
                                        echo esc_html($countries[$code]);
                                ?>
                                
                            </div>                         
                        </div>
                        <div class="col-2 col-md-1 order-2 order-md-3 d-flex justify-content-end">
                            <div class="edit-checkout">
                                <a href="/checkout/">
                                    <svg class="icon icon-edit" aria-hidden="true">
                                        <use xlink:href="#shape-checkout-edit"></use>
                                    </svg>    
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="checkout-wrapper-steps">
                    <div class="row align-items-start">
                        <div class="col-10 col-md-3 col-lg-3">
                            <div class="checkout-step"><?php echo __('Step 2', 'propeller-ecommerce'); ?></div>
                            <div class="checkout-title"><?php echo __('Delivery', 'propeller-ecommerce'); ?></div>
                        </div>
                        <div class="col-12 col-md-7 col-lg-7 ml-md-auto order-3 order-md-2 user-details">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="addr-title"><?php echo __('Delivery address', 'propeller-ecommerce'); ?></div>
                                    <div class="user-addr-details">
                                        <?php echo esc_html($delivery_address->company); ?><br>
                                        <?php if ($delivery_address->gender === 'M') {
                                                echo SALUTATION_M;
                                            }
                                            else if ($delivery_address->gender === 'F') {
                                                echo SALUTATION_F;
                                            }
                                            else {
                                                echo SALUTATION_U;
                                            }
                                                
                                        ?> <?php echo esc_html($delivery_address->firstName); ?> <?php echo esc_html($delivery_address->lastName); ?><br>
                                        <?php echo esc_html($delivery_address->street); ?> <?php echo esc_html($delivery_address->number); ?> <?php echo esc_html($delivery_address->numberExtension); ?><br>
                                        <?php echo esc_html($delivery_address->postalCode); ?> <?php echo esc_html($delivery_address->city); ?><br>
                                        <?php 
                                            $code = $delivery_address->country;
                                            $countries = include PROPELLER_PLUGIN_DIR . '/includes/Countries.php'; 

                                            if( !$countries[$code] ) 
                                                echo esc_html($code);
                                            else 
                                                echo esc_html($countries[$code]);
                                        ?>
                                        
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="addr-title"><?php echo __('Shipping', 'propeller-ecommerce'); ?></div>
                                    <div class="user-addr-details">
                                    <?php echo esc_html($this->cart->carrier); ?> - <span class="price"><span class="symbol">&euro;&nbsp;</span>
                                        <?php if(isset($this->cart->carriers) && !empty($this->cart->carriers)) {
                                            foreach ($this->cart->carriers as $carrier) {
                                                if ($this->cart->carrier == $carrier->name)
                                                    echo PropellerHelper::formatPrice($carrier->price);
                                            }
                                        }?>
                                       
                                        </span><br>
                                        <!-- Verwacht bezorgmoment:<br>
                                        <span class="delivery-date">Woensdag 28 juli</span>                                         -->
                                    </div>
                                </div>
                            </div>
                         
                        </div>
                        <div class="col-2 col-md-1 order-2 order-md-3 d-flex justify-content-end">
                            <div class="edit-checkout">
                                <a href="<?php echo esc_url($this->buildUrl(PageController::get_slug(PageType::CHECKOUT_PAGE),  '2')); ?>">
                                    <svg class="icon icon-edit" aria-hidden="true">
                                        <use xlink:href="#shape-checkout-edit"></use>
                                    </svg>    
                                </a>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="checkout-wrapper-steps">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <div class="checkout-step"><?php echo __('Step 3', 'propeller-ecommerce'); ?></div>
                            <div class="checkout-title"><?php echo __('Payment method', 'propeller-ecommerce'); ?></div>
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <div class="checkout-step-nr">3/3</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <form name="checkout-paymethod" class="form-handler checkout-form validate" method="post">
                                <input type="hidden" name="action" value="cart_step_3" />
                                <input type="hidden" name="step" value="<?php echo esc_attr($slug); ?>" />
                                <input type="hidden" name="next_step" value="summary" />
                                <input type="hidden" name="icp" value="N" />
                                <fieldset>
                                    <div class="row form-group">
                                        <div class="col-form-fields col-12 col-md-8">
                                            <div class="row px-2 d-flex form-row form-check paymethods">
                                                <?php foreach($pay_methods as $payMethod) { ?>
                                                    <div class="col-6 col-md-3 mb-4">
                                                        <label class="form-check-label paymethod">
                                                            <span class="row d-flex align-items-center text-center">
                                                                <input type="radio" name="payMethod" value="<?php echo esc_attr($payMethod->code); ?>" title="Select paymethod" data-rule-required="true" required="required" aria-required="true" class="required" data-rule-required="true" required="required" aria-required="true" class="required" />
                                                                <div class="paymethod-img col-12">
                                                                    <svg class="icon icon-paymethod-logo" aria-hidden="true">
                                                                        <use xlink:href="#shape-<?php echo esc_attr($payMethod->description); ?>"></use>
                                                                    </svg> 
                                                                </div> 
                                                                <div class="paymethod-name col-12"><?php echo esc_html($payMethod->description); ?></div>
                                                                <div class="paymethod-cost col-12"><span class="currency">&euro;</span> <?php echo esc_html($payMethod->price); ?></div>
                                                            </span>
                                                        </label>                                                
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                
                                <div class="row form-group form-group-submit">
                                    <div class="col-form-fields col-12">
                                        <div class="form-row">
                                            <div class="col-12 col-md-8">
                                                <button type="submit" class="btn-proceed btn-green"><?php echo __('Order overview & payment', 'propeller-ecommerce'); ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                
                <?php include $this->partials_dir .'/cart/propeller-shopping-cart-totals.php'?>   
            </div>
        </div>
    </div>
</div>

<?php include $this->partials_dir . '/other/propeller-toast.php'; ?>