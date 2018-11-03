<?php
/**
 * backend/config.php
 *
 * Author: pixelcave
 *
 * Codebase - Backend papges configuration file
 *
 */

// **************************************************************************************************
// BACKEND INCLUDED VIEWS
// **************************************************************************************************

//                              : Useful for adding different sidebars/headers per page or per section
//$cb->inc_side_overlay           = 'inc/backend/views/inc_side_overlay.php';
$cb->inc_sidebar                = 'inc/backend/views/inc_sidebar.php';
$cb->inc_header                 = 'inc/backend/views/inc_header.php';
$cb->inc_footer                 = 'inc/backend/views/inc_footer.php';


// **************************************************************************************************
// BACKEND MAIN MENU
// **************************************************************************************************

// You can use the following array to create your main menu
$cb->main_nav                   = array(
    //dashboard
	array(
        'name'  => '<span class="sidebar-mini-hide">Dashboard</span>',
        'icon'  => 'si si-home',
        'url'   => 'dashboard.php'
    ),
    //seperator
    array(
        'name'  => '<span class="sidebar-mini-visible">UI</span><span class="sidebar-mini-hidden">Main Modules</span>',
        'type'  => 'heading'
    ),
	//reservation management
    array(
        'name'  => '<span class="sidebar-mini-hide">Reservation Mangement</span>',
        'icon'  => 'fa fa-bookmark-o',
        'sub'   => array(
            array(
                'name'  => 'Add Reservation',
                'url'   => 'reservation_add.php'
            ),
            array(
                'name'  => 'Modify Reservation',
                'url'   => 'reservation_modify.php'
            ),
        )
    ),
    //end reservation

    //staff management
    array(
        'name'  => '<span class="sidebar-mini-hide">Staff Mangement</span>',
        'icon'  => 'fa fa-user-o',
        'sub'   => array(
            array(
                'name'  => 'Add Staff Type',
                'url'   => 'staff_type_add.php'
            ),
            array(
                'name'  => 'Modify Staff Type',
                'url'   => 'staff_type_modify.php'
            ),
            array(
                'name'  => 'Add Staff',
                'url'   => 'staff_add.php'
            ),
            array(
                'name'  => 'Modify Staff',
                'url'   => 'staff_modify.php'
            ),          
        )
    ),
    //end staff management

    //customer management
    array(
        'name'  => '<span class="sidebar-mini-hide">Customer Management</span>',
        'icon'  => 'fa fa-users',
        'sub'   => array(
            array(
                'name'  => 'Add Customer',
                'url'   => 'customer_add.php'
            ),
            array(
                'name'  => 'Modify Customer',
                'url'   => 'customer_modify.php'
            ),
        )
    ),
    //end cutomer management

    //checked in
	array(
        'name'  => '<span class="sidebar-mini-hide">Check In Customer</span>',
        'icon'  => 'fa fa-calendar-check-o',
        'url'   => 'in_customer.php'
    ),
    //end checked in

    //checked out
	array(
        'name'  => '<span class="sidebar-mini-hide">Check Out Customer</span>',
        'icon'  => 'fa fa-sign-out',
        'url'   => 'out_customer.php'
    ),
    //end checked out

    //payment management
    array(
        'name'  => '<span class="sidebar-mini-hide">Payment Mangement</span>',
        'icon'  => 'fa fa-dollar',           
        'sub'   => array(            
            array(
                'name'  => 'Add Payment',
                'url'   => 'payment_add.php'
            ),
            array(
                'name'  => 'View/Print Invoice',
                'url'   => 'invoice_view.php'
            ),
        )         
    ),
    //end payment mangement

    //room management    
    array(
        'name'  => '<span class="sidebar-mini-hide">Room Management</span>',
        'icon'  => 'fa fa-building-o',
        'sub'   => array(            
            array(
                'name'  => 'Add Room Facility',
                'url'   => 'facility_add.php'
            ),
            array(
                'name'  => 'Modify Room Facility',
                'url'   => 'facility_modify.php'
            ),
            array(
                'name'  => 'Add Room Type',
                'url'   => 'room_type_add.php'
            ),
            array(
                'name'  => 'Modify Room Type',
                'url'   => 'room_type_modify.php'
            ),
            array(
                'name'  => 'Add Room',
                'url'   => 'room_add.php'
            ),
            array(
                'name'  => 'Modify Room',
                'url'   => 'room_modify.php'
            ),
        )
    ),
    //end room mangement

    //schedule staffs
    array(
        'name'  => '<span class="sidebar-mini-hide">Schedule Staff</span>',
        'icon'  => 'si si-calendar',
        'sub'   => array(
            array(
                'name'  => 'Add Staff Schedule',
                'url'   => '#'
            ),
            array(
                'name'  => 'Modify Staff Schedule',
                'url'   => '#'
            ),
        )
    ),
    //end shedule staffs

    //generate reports
    array(
        'name'  => '<span class="sidebar-mini-hide">Generate Reports</span>',
        'icon'  => 'si si-bar-chart',
        'sub'   => array(
            array(
                'name'  => 'Reservation List',
                'url'   => '#'
            ),
            array(
                'name'  => 'Staff List',
                'url'   => ''
            ),            
            array(
                'name'  => 'Customer List',
                'url'   => ''
            ),            
            array(
                'name'  => 'Payment List',
                'url'   => '#'
            ),
            array(
                'name'  => 'Checked In Users',
                'url'   => 'users_checked_in.php'
            ),
            array(
                'name'  => 'Checked Out Users',
                'url'   => 'users_checked_out.php'
            ),
        )
    ),
    //end generate reports

    //users
    array(
        'name'  => '<span class="sidebar-mini-hide">User Management</span>',
        'icon'  => 'si si-users',
        'sub'   => array(
            array(
                'name'  => 'Add User',
                'url'   => 'user_add.php'
            ),
            array(
                'name'  => 'Modify User',
                'url'   => 'user_modify.php'
            ),
        )
    ),
    //end users

    //utilities
    array(
        'name'  => '<span class="sidebar-mini-hide">Utilities</span>',
        'icon'  => 'si si-settings',
        'sub'   => array(
            array(
                'name'  => 'Change Password',
                'url'   => 'generic_profile.php'
            ),
            array(
                'name'  => 'Backup Database',
                'url'   => '#'
            ),
            array(
                'name'  => 'Restore Database',
                'url'   => '#'
            ),
        )
    ),
    //end utilities  
);
