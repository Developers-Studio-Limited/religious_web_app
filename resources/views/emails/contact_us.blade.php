<!DOCTYPE html>
<html lang="en">

    <head>
        <title>Contact Us</title>
        <meta charset="utf-8">
        <link rel="shortcut icon" type="image/png" href="../../assets/images/dummy/eco-1024 crop.png" />  
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <!-- font-family: 'Lato', sans-serif; -->
        <style>
            .table{
                border:1px solid black;
            }
        </style>
    </head>


    <body style=" font-family: Arial, Helvetica, sans-serif !important;">
        
        <div class="container " style="padding: 50px 0px; text-align: center;">



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
                        ">Contact Us</h4>

                </div>

                <div class="col-md-12">
                    <div>
                        <p style="
                           font-size: 17px;
                           color: #000;
                           line-height: 1.2;
                           font-weight: 500;">Hello! Admin,</p>
                        <p style="
                           font-size: 14px;
                           color: #848484;
                           line-height: 1.2;
                           letter-spacing: 0.03rem;">This is contact us form <br>

                        </p>
                    </div>
                </div>

                <div class="col-md-12">
                    <div>
        <table class="table table-bordered" style="margin: auto;">
            <thead>
                <tr>
                    <th colspan="3" style="text-align: center;">Contact Us Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Name</td>
                    <td>{{ $user['name'] }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{ $user['email'] }}</td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td>{{ $user['phone'] }}</td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><div>{{ $user['description'] }}</div></td>
                </tr>
                
            </tbody>
        </table>
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