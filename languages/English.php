<?php

$language = array();

//Errors
$language['errors']['user_exists']                                              = "We're sorry, but the <strong>%s</strong> has already been used !";
$language['errors']['email_doesnt_exist']                                       = "The introduced email does not exist !";
$language['errors']['mobile_digit_greater']                                     ="Mobile number is shorter or longer than 14 digits";
$language['errors']['empty_fields']                                             = "You must fill all the fields !";
$language['errors']['empty_field_url']                                          = "You must fill url field !";
$language['errors']['empty_field_title']                                        = "You must fill the page title field !";
$language['errors']['login_failed']                                             = "We couldn't sign you in with those credentials !";
$language['errors']['cant_access']                                              = "You can't access this page at the moment !";
$language['errors']['name_length']                                              = "Name must be between 3 and 32 characters !";
$language['errors']['invalid_email']                                            = "You entered an invalid email !";
$language['errors']['email_used']                                               = "We are sorry but that email is already used !";
$language['errors']['password_too_short']                                       = "Your password is too short, it must be at least 6 characters long !";
$language['errors']['passwords_doesnt_match']                     		= "Password do not match !";
$language['errors']['password_user_doesnt_match']                     		= "Password and username do not match !";
$language['errors']['delete_yourself']	 					= "We're sorry but you can't delete your own account !";
$language['errors']['command_denied']	 					= "You don't have access to this command!";
$language['errors']['fields_required']						= "All fields are required !";
$language['errors']['already_logged_in']                           	        = "Welcome back, we missed you!";
$language['errors']['logged_in']                                                = "You have successfully logged in!";
$language['errors']['user_added']                                               = "Client has been created successfully!";
$language['errors']['product_added']                                            = "Product has been created successfully!";
$language['errors']['empty_reply']                                              = "Reply box cannot be empty!";
$language['errors']['reply_limit']                                              = "You have exceeded the limit of reply characters you can use, limit is 2560 characters";
$language['errors']['meta_limit']                                               = "You have exceeded the limit of description characters you can use, limit is 2560 characters";
$language['errors']['description_limit']                                        = "You have exceeded the limit of description characters you can use, limit is 1024 characters";
$language['errors']['disclaimer_limit']                                         = "You have exceeded the limit of invoice disclaimer characters you can use, limit is 2560 characters";
$language['errors']['reply_success']                                            = "Your reply was submitted";
$language['errors']['update_success']                                           = "Report was updated to %s !";
$language['errors']['report_created']                                           = "Report was created successfully !";
$language['errors']['comment_success']                                          = "Your comment was submitted";
$language['errors']['comment_limit']                                            = "You have exceeded the limit of reply characters you can use, limit is 1024 characters";
$language['errors']['profile_edited']                                           = "Your profile was successfully changed!";
$language['errors']['product_edited']                                           = "Product was successfully updated!";
$language['errors']['settings_updated']                                         = "Settings were successfully updated!";
$language['errors']['add_invoice']                                              = "Invoice was successfully created!";
$language['errors']['edit_invoice']                                             = "Invoice was successfully edited!";

//Notifications
$language['notifications']['new_report']                                        = "A new report has been received";
$language['notifications']['new_reply']                                         = "A new reply on your report has been received";
$language['notifications']['new_invoice']                                       = "A new invoice has been created!";
$language['notifications']['payment']                                           = "%s has made a payment on invoice %s";
$language['notifications']['none']                                              = "No notifications!";

//Status
$language['status']['read']                                                     = "Read";
$language['status']['unread']                                                   = "Unread";
$language['status']['done']                                                     = "Done";
$language['status']['cancelled']                                                = "Cancelled";
$language['status']['paid']                                                     = "Paid";
$language['status']['over_due']                                                  = "Overdue";
$language['status']['deposit']                                                  = "Deposit";
$language['status']['draft']                                                    = "Draft";
$language['status']['unpaid']                                                   = "Unpaid";

//Menu
$language['menu']['settings']                                                   = "Settings";
$language['menu']['sign_out']                                                   = "Sign Out";
$language['menu']['report']                                                     = "Reports";
$language['menu']['invoices']                                                   = "Invoices";
$language['menu']['details']                                                    = "Details";
$language['menu']['view_invoices']                                              = "View Invoices";
$language['menu']['create_invoices']                                            = "Create Invoice";
$language['menu']['payment']                                                    = "Payments";
$language['menu']['product']                                                    = "Products";
$language['menu']['view_products']                                              = "View Products";
$language['menu']['create_products']                                            = "Create Product";
$language['menu']['user']                                                       = "Clients";
$language['menu']['dashboard']                                                  = "Dashboard";
$language['menu']['view_users']                                                 = "View Clients";
$language['menu']['create_users']                                               = "Create Client";
$language['menu']['view_reports']                                               = "View Reports";
$language['menu']['payment_notification']                                       = "Send payment notification";


//Alerts
$language['alerts']['error']                                                    = "Error!";
$language['alerts']['warning']                                                  = "Warning!";
$language['alerts']['success'] 	 						= "Congratulations!";
$language['alerts']['info']  	 						= "Notice!";
$language['misc']['remove_report']                                              = "Are you sure you want to remove this report?";
$language['misc']['remove_user']                                                = "Are you sure you want to remove this client?";
$language['misc']['remove_product']                                             = "Are you sure you want to remove this product? They will also be removed from invoices";
$language['misc']['remove_invoice']                                             = "Are you sure you want to remove this invoice?";


//Emails
$language['emails']['invoice_title']                                            = "New Invoice";
$language['emails']['invoice_message']                                          = "A new invoice has been created";
$language['emails']['invoice_message_view']                                     = "View your invoice here: %s";
$language['emails']['invoice_status']                                           = "Your invoice status: ";
$language['misc']['registration_email_message']                                 = "An admin has invited you to login to your account and monitor your invoices.";
$language['misc']['registration_mail_title']                                    = "Account Created";
$language['emails']['changepass_mail_title']                                    = "Password Changed";
$language['emails']['changepass_mail_message']                                  = "Your password has been changed successfully. ";
$language['emails']['pay_not_message']                                          = "This is a payment reminder for your invoice";
$language['emails']['payment_title']                                            = "Reminder For Payment";


//Dashboard
$language['misc']['total_income']                                               = "Total Income";
$language['misc']['monthly_income']                                             = "Monthly Income";
$language['misc']['invoice_stats']                                              = "Invoices Statistics";
$language['misc']['total_clients']                                              = "Total Clients";
$language['misc']['total_reports']                                              = "Total Reports";

//Invoices
$language['menu']['invoice_update']                                             = "Update Invoice";
$language['menu']['invoice_remove']                                             = "Remove Invoice";
$language['menu']['save_draft']                                                 = "Save draft";
$language['menu']['remove_draft']                                               = "Remove from drafts";
$language['menu']['mark_cancelled']                                             = "Mark as cancelled";
$language['invoice']['create_invoice']                                          = "Create Invoice";
$language['invoice']['update_invoice']                                          = "Update Invoice";
$language['forms']['payment_type']                                              = "Payment Type";
$language['invoice']['to']                                                      = "To";
$language['invoice']['from']                                                    = "From";
$language['invoice']['deposit']                                                 = "Deposit";
$language['invoice']['pay_now']                                                 = "Pay Now";
$language['invoice']['quantity']                                                = "QTY";
$language['invoice']['product']                                                 = "Product";
$language['invoice']['total']                                                   = "Total";
$language['invoice']['tax']                                                     = "Tax";
$language['invoice']['sub_total']                                               = "Sub Total";
$language['invoice']['discount']                                                = "Discount";
$language['forms']['due_date']                                                  = "Due Date";
$language['forms']['set_status']                                                = "Set status to done";
$language['misc']['no_due_date']                                                = "No due date";
$language['misc']['report_invoice']                                             = "Report invoice";
$language['misc']['invoice_download']                                           = "Download PDF";
$language['misc']['invoice_options']                                            = "Options";
$language['misc']['invoice_email']                                              = "Email invoice to client";
$language['misc']['invoice_email_client']                                       = "Email invoice to %s";
$language['invoice']['section_message']                                         = "Use this section for non registered clients";


//Payments
$language['payments']['revenue']                                                = "Revenue";
$language['payments']['form']                                                   = "Form";
$language['misc']['full_payment']                                               = "Full Payment";
$language['misc']['deposit']                                                    = "Deposit";


//Settings
$language['forms']['page_title']                                                = "Page title";
$language['forms']['url']                                                       = "URL";
$language['forms']['meta_description']                                          = "Website Description";
$language['forms']['analytics_code']                                            = "Google Analytics Code";
$language['forms']['pagination']                                                = "Results To Show Per Page";
$language['forms']['contact_email']                                             = "Contact Email";
$language['forms']['invoice_disclaimer']                                        = "Invoice Disclaimer";
$language['forms']['paypal_email']                                              = "Paypal Email";
$language['forms']['payment_currency']                                          = "Payment Currency";
$language['forms']['payment_prefix']                                            = "Payment Prefix";
$language['forms']['product_prefix']                                            = "Product Prefix";
$language['forms']['report_prefix']                                             = "Report Prefix";
$language['forms']['user_prefix']                                               = "User Prefix";
$language['forms']['change_password']                                               = "Change Password";
$language['forms']['currency_prefix']                                           = "Currency Prefix";
$language['forms']['invoice_prefix']                                            = "Invoice Prefix";
$language['forms']['logo']                                                      = "Logo";
$language['forms']['favicon']                                                   = "Favicon";
$language['forms']['main_settings']                                             = "Main";
$language['forms']['prefix_settings']                                           = "Prefix";
$language['forms']['logos']                                                     = "Logos";
$language['forms']['payment_settings']                                          = "Payment";
$language['forms']['invoice_settings']                                          = "Invoice";
$language['forms']['address']                                                   = "Company Address";
$language['forms']['save_settings']                                             = "Save Settings";

//Client
$language['forms']['username']                                                  = "Email";
$language['forms']['remember_me']                                               = "Remember Me ";
$language['forms']['sign_in']                                                   = "Login";
$language['forms']['lost_password']                                             = "Forgot Password? ";
$language['forms']['password']                                                  = "Password";
$language['forms']['confirm_password']                                          = "Confirm Password";
$language['forms']['contact_number']                                            = "Contact Number";
$language['forms']['first_name']                                                = "First Name";
$language['forms']['last_name']                                                 = "Last Name";
$language['forms']['location']                                                  = "Country";
$language['forms']['company_name']                                              = "Company Name";
$language['forms']['company_address']                                           = "Company Address";
$language['forms']['contact_number']                                            = "Contact Number";
$language['forms']['client']                                                    = "Client";
$language['forms']['add_comment']                                               = "Add a comment on this client, NOTE: client will not see the comment";
$language['forms']['client_pic']                                                = "Change Picture: ";
$language['forms']['comment']                                                   = "Add Comment";
$language['forms']['add_client']                                                = "Add Client";
$language['forms']['edit_profile']                                              = "Edit Account";
$language['misc']['total_invoices']                                             = "Total Invoices";
$language['misc']['register_date']                                              = "Reg. Date";
$language['misc']['role']                                                       = "Role";
$language['misc']['role_client']                                                = "Client";
$language['misc']['role_store_manager']                                         = "Store Manager";
$language['misc']['role_accountant']                                            = "Accountant";
$language['misc']['role_manager']                                               = "Manager";
$language['misc']['role_founder']                                               = "Founder";
$language['misc']['comments']                                                   = "Comments";

//Products
$language['forms']['product_name']                                              = "Product Name";
$language['forms']['product_description']                                       = "Product Description";
$language['forms']['product_price']                                             = "Product Price";
$language['forms']['product_unit']                                              = "Unit";
$language['forms']['product_button']                                            = "Save Changes";

//Reports
$language['forms']['add_reply']                                                 = "Reply on this report";
$language['report']['type_report']                                              = "Type in your report";
$language['forms']['submit']                                                    = "Submit";
$language['forms']['reply']                                                     = "Reply";
$language['misc']['report']                                                     = "Report";
$language['misc']['replies']                                                    = "Replies";

//Misc
$language['misc']['report_id']                                                  = "Report ID";
$language['misc']['invoice_id']                                                 = "Invoice ID";
$language['misc']['user_id']                                                    = "User ID";
$language['misc']['payment_id']                                                 = "Payment ID";
$language['misc']['product_id']                                                 = "Product ID";
$language['misc']['mark_paid']                                                  = "Mark as paid";
$language['misc']['user_full_name']                                             = "Full Name";
$language['misc']['status']                                                     = "Status";
$language['misc']['user_type']                                                  = "Type";
$language['misc']['user_type_change']                                           = "Change Type";
$language['misc']['date']                                                       = "Date";
$language['misc']['view']                                                       = "View";
$language['forms']['version']                                                   = "Current Version:";


	

?>