<?php
    use Propeller\PropellerHelper;
    use Propeller\Includes\Controller\PageController;
    use Propeller\Includes\Controller\UserController;
    use Propeller\Includes\Enum\PageType;
    wp_enqueue_style('datatables_css', 'https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css', array(), null, 'all');
    wp_enqueue_script('datatables', 'https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js', array( 'jquery'), null, false);
    wp_enqueue_script('datatables_init', $this->assets_url . '/js/parts/datatables.js', array( 'jquery'), null, true);

?>
<?php if (!UserController::is_logged_in()) {
      wp_redirect('/' . PageController::get_slug(PageType::LOGIN_PAGE));
      exit;
 } else { ?>
<svg style="display:none;">
    <symbol viewBox="0 0 5 8" id="shape-arrow-left"><title>Arrow left</title><path d="M4.173 7.85a.546.546 0 0 1-.771-.02L.149 4.375a.545.545 0 0 1 0-.75L3.402.17a.546.546 0 0 1 .792.75L1.276 4l2.918 3.08a.545.545 0 0 1-.021.77z" /></symbol>  
    <symbol viewBox="0 0 12 16" id="shape-download"><title>PDF file</title> <g><path d="M10.5 16c.417 0 .77-.146 1.062-.438.292-.291.438-.645.438-1.062V4.125c0-.417-.146-.77-.438-1.062L8.937.438A1.443 1.443 0 007.875 0H1.5C1.083 0 .73.146.437.438A1.446 1.446 0 000 1.5v13c0 .417.146.77.437 1.062.292.292.646.438 1.063.438h9zM11 4H8V1a.87.87 0 01.219.156l2.625 2.625A.87.87 0 0111 4zm-.5 11h-9a.49.49 0 01-.36-.14.49.49 0 01-.14-.36v-13a.49.49 0 01.14-.36A.49.49 0 011.5 1H7v3.25c0 .208.073.385.219.531A.723.723 0 007.75 5H11v9.5a.49.49 0 01-.14.36.49.49 0 01-.36.14zm-7.809-1.997L2.812 13c.292-.042.579-.219.86-.531.281-.313.61-.792.984-1.438l.469-.156c.958-.312 1.656-.51 2.094-.594.333.188.687.339 1.062.453.375.115.693.172.953.172s.459-.078.594-.234a.694.694 0 00.172-.547c-.02-.208-.083-.365-.188-.469C9.5 9.344 8.73 9.271 7.5 9.438c-.625-.375-1.094-.97-1.406-1.782l.031-.093a6.76 6.76 0 00.187-.97c.063-.395.063-.708 0-.937-.041-.291-.166-.484-.375-.578a.85.85 0 00-.64-.031c-.219.073-.35.203-.39.39-.084.271-.095.615-.032 1.032.042.354.135.823.281 1.406-.479 1.167-.916 2.083-1.312 2.75C2.74 11.208 2.125 11.76 2 12.281c-.02.188.047.36.203.516.156.156.36.224.61.203l-.122.003zm2.996-6.034c-.083-.23-.125-.531-.125-.906s.021-.563.063-.563v-.031c.125 0 .193.208.203.625.01.416-.036.708-.14.875zm-.937 3.5c.27-.5.562-1.167.875-2 .292.541.656.979 1.094 1.312-.375.063-.896.23-1.563.5l-.406.188zm4.687-.156c-.083.02-.208.01-.375-.032A3.772 3.772 0 018.187 10c.375-.042.688-.042.938 0 .187.042.318.089.39.14.074.053.079.1.016.141-.02.021-.052.032-.094.032zm-6.775 2.13c.098-.297.432-.693.994-1.193l.094-.094a5.837 5.837 0 01-.594.813c-.125.166-.24.291-.344.375-.104.083-.156.114-.156.094l.006.006z"/></g>  </symbol>
</svg>
<div class="container-fluid px-0 propeller-account-wrapper propeller-favorites-wrapper">
    <?php 
        echo '<script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [
                { 
                    "@type": "ListItem",
                    "position": 1,
                    "item": {
                        "@type": "Thing",
                        "@id": " '.  home_url() .' " , 
                        "name": "'?> <?php echo __("Home","propeller-ecommerce") ?><?php echo '"
                    }
                },
                {
                    "@type": "ListItem",
                    "position": 2,
                    "item": {
                        "@type": "Thing",
                        "@id": "' . $this->buildUrl('',PageController::get_slug(PageType::MY_ACCOUNT_PAGE)) . '" , 
                        "name": "'?> <?php  echo __("My account", "propeller-ecommerce") ?><?php echo '"
                    }
                }
            ]
        }
        </script>';
    ?>
    <div class="row">
        <div class="col">
            <?php
                $breadcrumb_paths = [
                    [
                        $this->buildUrl('',PageController::get_slug(PageType::MY_ACCOUNT_MOBILE_PAGE)), 
                        __('My account', 'propeller-ecommerce')
                    ],
                    [
                        $this->buildUrl('',PageController::get_slug(PageType::MY_ACCOUNT_PAGE)),
                        __('My account', 'propeller-ecommerce')
                    ],
                    [
                        $this->buildUrl('',PageController::get_slug(PageType::INVOICES_PAGE)),
                        __('Invoices', 'propeller-ecommerce')
                    ]
                ];

                apply_filters('propel_breadcrumbs', $breadcrumb_paths);
            ?>
        </div>
    </div>
    <duv class="row">
        <?php echo apply_filters('propel_my_account_title', __('My account', 'propeller-ecommerce')); ?> 
    </duv>
    <div class="row">
        <div class="col-12 col-lg-3">
            <?php echo apply_filters('propel_my_account_menu', $this); ?>
        </div>
        <div class="col-12 col-lg-9">
            <div class="propeller-account-table propeller-invoices-table">
                <div class="back-link d-flex d-md-none">
                    <a href="/my-account/">
                        <svg class="icon icon-svg icon-arrow-left" aria-hidden="true">
                            <use class="icon-shape-arrow-left" xlink:href="#shape-arrow-left"></use>
                        </svg>
                        <?php echo __('My account', 'propeller-ecommerce'); ?>
                    </a>
                </div>
                <h4><?php echo __('My invoices', 'propeller-ecommerce'); ?></h4>
                <div class="table-sort-select col-6 px-0 d-flex d-md-none align-items-center">
                        <label class="label"><?php echo __('Sort by', 'propeller-ecommerce'); ?></label>
                        <div class="dropdown sticky-dropdown-menu">
                            <select id="sortTable" name="table-sort" class="form-control">                              
                                <option value="order_no"><?php echo __('Code', 'propeller-ecommerce'); ?></option>
                                <option value="order_date"><?php echo __('Date', 'propeller-ecommerce'); ?></option>
                                <option value="order_quantity"><?php echo __('Total', 'propeller-ecommerce'); ?></option>
                                <option value="order_total"><?php echo __('Status', 'propeller-ecommerce'); ?></option>
                                <option value="order_status"><?php echo __('Download', 'propeller-ecommerce'); ?></option>
                            </select>
                        </div>
                    </div>
                    <table class="table-sorter">
                        <thead>
                            <tr>
                                <th><?php echo __('Code', 'propeller-ecommerce'); ?></th>
                                <th class="min-tablet-l"><?php echo __('Date', 'propeller-ecommerce'); ?></th>
                                <th class="min-tablet-l"><?php echo __('Total', 'propeller-ecommerce'); ?></th>
                                <th class="min-tablet-l"><?php echo __('Status', 'propeller-ecommerce'); ?></th>
                                <th><?php echo __('Download', 'propeller-ecommerce'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                          
                                <tr>
                                    <td><span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Code', 'propeller-ecommerce'); ?></span><span class="col-auto">0000016</span></td>
                                    <td><span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Date', 'propeller-ecommerce'); ?></span><span class="col-auto">10/01/2022</span></td>
                                    <td><span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Total', 'propeller-ecommerce'); ?></span><span class="col-auto"><span class="symbol">&euro;&nbsp;</span>556,66</td>
                                    <td><span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Status', 'propeller-ecommerce'); ?></span><span class="col-auto">Paid</span></td>
                                    <td>
                                        <span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Download', 'propeller-ecommerce'); ?></span>
                                        <div class="d-inline-flex col-auto">
                                            <a href="#" class="d-flex align-items-center download-pdf-link">            												 
                                                <svg class="icon icon-download icon-arrow-download" aria-hidden="true">
                                                    <use class="shape-download" xlink:href="#shape-download"></use>
                                                </svg>
                                                <span>
                                                    <?php echo __('Download PDF', 'propeller-ecommerce'); ?>
                                                </span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Code', 'propeller-ecommerce'); ?></span><span class="col-auto">0000022</span></td>
                                    <td><span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Date', 'propeller-ecommerce'); ?></span><span class="col-auto">22/01/2022</span></td>
                                    <td><span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Total', 'propeller-ecommerce'); ?></span><span class="col-auto"><span class="symbol">&euro;&nbsp;</span>7.556,66</td>
                                    <td><span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Status', 'propeller-ecommerce'); ?></span><span class="col-auto">Paid</span></td>
                                    <td>
                                        <span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Download', 'propeller-ecommerce'); ?></span>
                                        <div class="d-inline-flex col-auto">
                                            <a href="#" class="d-flex align-items-center download-pdf-link">            												 
                                                <svg class="icon icon-download icon-arrow-download" aria-hidden="true">
                                                    <use class="shape-download" xlink:href="#shape-download"></use>
                                                </svg>
                                                <span>
                                                    <?php echo __('Download PDF', 'propeller-ecommerce'); ?>
                                                </span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Code', 'propeller-ecommerce'); ?></span><span class="col-auto">0000026</span></td>
                                    <td><span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Date', 'propeller-ecommerce'); ?></span><span class="col-auto">15/02/2022</span></td>
                                    <td><span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Total', 'propeller-ecommerce'); ?></span><span class="col-auto"><span class="symbol">&euro;&nbsp;</span>5.667,20</td>
                                    <td><span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Status', 'propeller-ecommerce'); ?></span><span class="col-auto">Paid</span></td>
                                    <td>
                                        <span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Download', 'propeller-ecommerce'); ?></span>
                                        <div class="d-inline-flex col-auto">
                                            <a href="#" class="d-flex align-items-center download-pdf-link">            												 
                                                <svg class="icon icon-download icon-arrow-download" aria-hidden="true">
                                                    <use class="shape-download" xlink:href="#shape-download"></use>
                                                </svg>
                                                <span>
                                                    <?php echo __('Download PDF', 'propeller-ecommerce'); ?>
                                                </span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Code', 'propeller-ecommerce'); ?></span><span class="col-auto">0000035</span></td>
                                    <td><span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Date', 'propeller-ecommerce'); ?></span><span class="col-auto">24/02/2022</span></td>
                                    <td><span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Total', 'propeller-ecommerce'); ?></span><span class="col-auto"><span class="symbol">&euro;&nbsp;</span>13.7878,00</td>
                                    <td><span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Status', 'propeller-ecommerce'); ?></span><span class="col-auto">Paid</span></td>
                                    <td>
                                        <span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Download', 'propeller-ecommerce'); ?></span>
                                        <div class="d-inline-flex col-auto">
                                            <a href="#" class="d-flex align-items-center download-pdf-link">            												 
                                                <svg class="icon icon-download icon-arrow-download" aria-hidden="true">
                                                    <use class="shape-download" xlink:href="#shape-download"></use>
                                                </svg>
                                                <span>
                                                    <?php echo __('Download PDF', 'propeller-ecommerce'); ?>
                                                </span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Code', 'propeller-ecommerce'); ?></span><span class="col-auto">0000040</span></td>
                                    <td><span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Date', 'propeller-ecommerce'); ?></span><span class="col-auto">15/03/2022</span></td>
                                    <td><span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Total', 'propeller-ecommerce'); ?></span><span class="col-auto"><span class="symbol">&euro;&nbsp;</span>6.520,36</td>
                                    <td><span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Status', 'propeller-ecommerce'); ?></span><span class="col-auto">Paid</span></td>
                                    <td>
                                        <span class="table-label d-inline-block d-md-none col-5 col-sm-4 px-0"><?php echo __('Download', 'propeller-ecommerce'); ?></span>
                                        <div class="d-inline-flex col-auto">
                                            <a href="#" class="d-flex align-items-center download-pdf-link">            												 
                                                <svg class="icon icon-download icon-arrow-download" aria-hidden="true">
                                                    <use class="shape-download" xlink:href="#shape-download"></use>
                                                </svg>
                                                <span>
                                                    <?php echo __('Download PDF', 'propeller-ecommerce'); ?>
                                                </span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                                            
                        </tbody>
                    </table>
                   
                
                <?php ?>
                   
            </div>
           
        </div>
        
    </div>
    
</div>
<?php } ?>