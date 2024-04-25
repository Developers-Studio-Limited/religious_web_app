<!DOCTYPE html>
<html lang="en">

    <head>
        <title>Forgot Password</title>
        <meta charset="utf-8">
        <link rel="shortcut icon" type="image/png" href="../../assets/images/dummy/eco-1024 crop.png" />  
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
              integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <!-- font-family: 'Lato', sans-serif; -->

    </head>


    <body style=" font-family: Arial, Helvetica, sans-serif !important;">
        <div class="container " style="padding: 50px 0px; text-align: center;

             ">



            <div class="row  text-center" style="
                 padding: 20px 0px 0px 0px !important;
                 background: rgb(255,255,255) !important;
                 width:100%;
                 max-width: 600px !important;
                 margin: -5px auto !important;
                 text-align: center;
                 box-shadow: 0px 6px 12px rgba(0,0,0,0.16);
                 border: 1px solid #eee;">

                <div class="text-center " style="
                     width:100%;
                     max-width: 600px !important;
                     margin: 20px auto 0px !important;
                     padding: 10px 0px;
                     text-align: center;display: inline-block; ">
                    <img class="" style="width: 150px; 
                         object-fit: contain;" src="<?= asset('admin_assets/img/logos/app_icon.png') ?>">
                </div>

                <div class="col-md-12 ">
                    <h4 class="" style="
                        color: #006400;
                        font-size: 30px;
                        line-height: 1.167;
                        font-weight: 600;
                        margin-top: 0;
                        ">Forgot your password</h4>

                </div>



                <div class="col-md-12">
                    <div>
                        <p style="
                           font-size: 17px;
                           color: #000;
                           line-height: 1.2;
                           font-weight: 500;">Hello! <?= $name ?>,</p>
                        <p style="
                           font-size: 14px;
                           color: #848484;
                           line-height: 1.2;
                           letter-spacing: 0.03rem;">Looks like you forgot your password for Qalb e Saleem <br>
                            If this is correct, Click below to reset your password <br>

                        </p>
                        <div class="  login-form-footer" style="margin-top:30px;
                             margin-bottom:30px;">
                            <a href="<?= asset('forget-password/' . $token) ?>" class="home-form-btn " style="    font-size: 15px;
                               color: #fff !important;
                               background: #006400;  /* fallback for old browsers */
                               line-height: 1.5 !important;
                               font-weight: 700;
                               min-width: 250px;
                               height: 40px;
                               box-shadow: 0px 4px 16px rgba(0,0,0,0.16);
                               text-align: center;
                               border-radius: 6px;
                               text-decoration: none;
                               padding: 10px 20px;"> Reset my password</a><br><br>

                        </div>



                        <div>


                            <p style="
                               font-size: 14px;
                               margin-top: 40px;
                               color: #aaa9a9;
                               line-height: 0.2;
                               letter-spacing: 0.04rem;">If you did not forgot your password, Please ignore this email.

                            </p>
                            <p style="
                               font-size: 14px;
                               color: #848484;
                               line-height: 1.2;margin-top: 20px;margin-bottom: 0;">Thanks</p>
                            <p style="
                               font-size: 16px;
                               color: #000;
                               line-height: 1.6;
                               font-weight: 600;
                               letter-spacing: .04rem; margin-top: 2px;margin-bottom:0;">Qalb e Saleem Team</p>

                            <p style="
                               font-size: 14px;
                               color: #848484; margin-top: 0;
                               line-height: 1.2;">This is an automated message, Please do not reply.</p>
                        </div>
                        <div style="
                             padding: 10px 0px;
                             background-repeat: no-repeat;
                             background-size: cover;
                             background:#006400;
                             box-shadow: 0px -2px 8px 0px rgba(0,0,0,.3);
                             margin-top: 10px;">



                            <div>

                                <div>
                                    <p style="color: #fff;
                                       font-size: 14px;
                                       font-weight: 500;
                                       letter-spacing: 0.03em;
                                       padding: 15px 0px;
                                       /* margin-top: 8px; */
                                       margin: 0;">© <?= date('Y') ?> Qalb e Saleem All Rights Reserved</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>