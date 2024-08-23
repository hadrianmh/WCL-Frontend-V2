<?php
date_default_timezone_set("Asia/jakarta");

if(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "dashboard"){ ?>

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        SO Tracking
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo ucfirst(htmlspecialchars($_GET["page"])); ?></li>
      </ol>
    </section>

    <hr>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="form-group">
            <div class="input-group">
              <span class="input-group-addon">Sort by </span>
              <select id="sortby" class="form-control"></select>
              <span class="input-group-btn">
                <button type="button" id="LoadData" class="btn btn-default">View</button>
              </span>
            </div>
          </div>
        </div>
      </div>

      <div class="row diagramDASHBOARD">

        <?php if($_SESSION['role'] == '1' OR $_SESSION['role'] == '2' OR $_SESSION['role'] == '3' OR $_SESSION['role'] == '5'){ ?>

        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box" style="background-color:#28a745!important">
            <div class="inner">
              <h3 class="statistiPO">0</h3>
              <p>Preorder</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="index.php?page=preorder" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <?php } /* if($_SESSION['role'] == '1' OR $_SESSION['role'] == '2' OR $_SESSION['role'] == '5'){ ?>

        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box" style="background-color:#17a2b8!important">
            <div class="inner">
              <h3 class="statistiSPK">0</h3>
              <p>SPK selesai</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="index.php?page=workorder_done" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <?php } */ if($_SESSION['role'] == '1' OR $_SESSION['role'] == '2' OR $_SESSION['role'] == '5'){ ?>

        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box" style="background-color:#dc3545!important">
            <div class="inner">
              <h3 class="statistiSJ">0</h3>
              <p>Pengiriman selesai</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="index.php?page=delivery_orders_done" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <?php } if($_SESSION['role'] == '1' OR $_SESSION['role'] == '4' OR $_SESSION['role'] == '5'){ ?>

        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box" style="background-color:#ffc107!important">
            <div class="inner">
              <h3 class="statistiFAKTUR">0</h3>
              <p>Faktur selesai</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="index.php?page=invoice_done" class="small-box-footer">Selengkapnya <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <?php } ?>

        <?php if($_SESSION['role'] == '1' OR $_SESSION['email'] == 'iskandarwisnu7@gmail.com' OR $_SESSION['email'] == 'yudisepta3091@gmail.com' OR $_SESSION['email'] == 'riawidiastuti83@gmail.com'){ ?>
        <div class="col-lg-12">
          <table id="tablenya" class="datatable nowrap" style="display:none">
            <thead>
              <tr>
                <th>Company</th>
                <th>Order Grade</th>
                <th>No SO</th>
                <th>SO Date</th>
                <th>ETD</th>
                <th>Customer</th>
                <th>No PO</th>
                <th>PO Date</th>
                <th>Product</th>
                <th>Detail</th>
                <th>Merk</th>
                <th>Type</th>
                <th>Ukuran</th>
                <th>Kor</th>
                <th>Line</th>
                <th>Roll</th>
                <th>Bahan</th>
                <th>Porporasi</th>
                <th>Qty</th>
                <th>Unit</th>
                <th>Isi ROLL/PCS</th>
                <th>Uk Bahan Baku</th>
                <th>Qty Bahan Baku</th>
                <th>Catatan</th>
                <th>Sources</th>
                <th>Price</th>
                <th>Price Before</th>
                <th>Tax</th>
                <th>Total Amount</th>
                <th>Request Date</th>
                <th>Order Status</th>
                <th>No DN</th>
                <th>Delivery Date</th>
                <th>Courier Name</th>
                <th>Tracking No</th>
                <th>Remarks</th>
                <th>Cost</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>

        <!-- Modal Ongkir -->
          <div id="PeriodeResult" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close periode_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT PERIODE</h2>
              <form class="form add" id="form_periode" data-id="" novalidate>

                <div class="form-group">
                  <label>Dari Tanggal: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="startdate" id="startdate" required>
                </div>

                <div class="form-group">
                  <label>Sampai: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="enddate" id="enddate" required>
                </div>
        
                <div class="button_container" style="text-align: center">
                  <button type="submit" class="lihat">View</button>
                  <input type="button" class="periode_close" value="Cancel">
                  <input type="hidden" id="report" name="report" value="periode">
                </div>
              </form>
            </div>
          </div>
        <?php } ?>

          <noscript id="noscript_container">
            <div id="noscript" class="error">
              <p>JavaScript support is needed to use this page.</p>
            </div>
          </noscript>

          <div id="message_container">
            <div id="message" class="success">
              <p>This is a success message.</p>
            </div>
          </div>

          <div id="loading_container">
            <div id="loading_container2">
              <div id="loading_container3">
                <div id="loading_container4">
                  Loading, please wait...
                </div>
              </div>
            </div>
          </div>

      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "company"){ ?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo ucfirst(htmlspecialchars($_GET["page"])); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo ucfirst(htmlspecialchars($_GET["page"])); ?></li>
      </ol>
    </section>

    <hr>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">

        <div class ="container-fluid">
          <?php if($_SESSION['role'] != '5'){ ?>
            <button type="button" class="button" id="tambah_company">New Company</button>
          <?php } ?>
          <table id="tablenya" class="datatable responsive" style="width:100%">
            <thead>
              <tr>
                  <th>Company</th>
                  <th>Address</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Logo</th>
                  <th>Option</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

          <!-- The Modal -->
          <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT COMPANY</h2>
              <form class="form company_new" id="form_company" data-id="" novalidate>
                <hr>
                
                <div class="form-group company">
                  <label for="company">Nama Perusahaan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="company" id="company" required>
                </div>

                <div class="form-group address">
                  <label for="address">Alamat: <span class="required">*</span></label>
                  <textarea class="form-control" name="address" id="address" required></textarea>
                </div>

                <div class="form-group email">
                  <label for="address">Email: <span class="required">*</span></label>
                  <input type="email" class="form-control" name="email" id="email" required>
                </div>

                <div class="form-group phone">
                  <label for="phone">Telp: <span class="required">*</span></label>
                  <input type="number" class="form-control" name="phone" id="phone" required>
                </div>

                <div class="form-group logo margin-bottom-lg">
                  <label for="logo">Logo:</label>
                  <div class="row">
                    <div class="col-md-6 col-xs-12">
                      <input type="file" class="form-control" name="logo" id="logo" onchange="readImage(this);">
                      <input type="hidden" name="tmp_logo" id="tmp_logo" value="" data-img="">
                    </div>
                    <div class="col-md-6 col-xs-12">
                      <p>Allowed .png .jpg (max 1MB)</p>
                    </div>
                  </div>
                  <div class="row margin-x">
                    <div class="col-md-6 col-xs-12">
                      <div id="ImageResult"></div>
                    </div>
                    <div class="col-md-6 col-xs-12">
                      <input type="button" class="btn btn-danger" id="RemoveLogo" value="Remove" style="display: none">
                    </div>
                  </div>
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="saving">Simpan</button>
                  <input type="button" class="lightbox_close" value="Batal">
                </div>
              </form>
            </div>

          </div> 

          <noscript id="noscript_container">
            <div id="noscript" class="error">
              <p>JavaScript support is needed to use this page.</p>
            </div>
          </noscript>

          <div id="message_container">
            <div id="message" class="success">
              <p>This is a success message.</p>
            </div>
          </div>

          <div id="loading_container">
            <div id="loading_container2">
              <div id="loading_container3">
                <div id="loading_container4">
                  Loading, please wait...
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "vendor"){ ?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo ucfirst(htmlspecialchars($_GET["page"])); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo ucfirst(htmlspecialchars($_GET["page"])); ?></li>
      </ol>
    </section>

    <hr>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">

        <div class ="container-fluid">
          <?php if($_SESSION['role'] != '5'){ ?>
            <button type="button" class="button" id="tambah_vendor">New Vendor</button>
          <?php } ?>
          <table id="tablenya" class="datatable responsive" style="width:100%">
            <thead>
              <tr>
                  <th>Vendor Name</th>
                  <th>Address</th>
                  <th>Phone</th>
                  <th>Option</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

          <!-- The Modal -->
          <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT VENDOR</h2>
              <form class="form vendor_new" id="form_vendor" data-id="" novalidate>
                <hr>
                
                <div class="form-group vendor">
                  <label for="vendor">Nama Vendor: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="vendor" id="vendor" required>
                </div>

                <div class="form-group address">
                  <label for="address">Alamat: <span class="required">*</span></label>
                  <textarea class="form-control" name="address" id="address" required></textarea>
                </div>

                <div class="form-group phone">
                  <label for="phone">Telp: <span class="required">*</span></label>
                  <input type="number" min="0" class="form-control" name="phone" id="phone" required>
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="saving">Simpan</button>
                  <input type="button" class="lightbox_close" value="Batal">
                </div>
              </form>
            </div>

          </div>

          <noscript id="noscript_container">
            <div id="noscript" class="error">
              <p>JavaScript support is needed to use this page.</p>
            </div>
          </noscript>

          <div id="message_container">
            <div id="message" class="success">
              <p>This is a success message.</p>
            </div>
          </div>

          <div id="loading_container">
            <div id="loading_container2">
              <div id="loading_container3">
                <div id="loading_container4">
                  Loading, please wait...
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "customer"){ ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo ucfirst(htmlspecialchars($_GET["page"])); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo ucfirst(htmlspecialchars($_GET["page"])); ?></li>
      </ol>
    </section>

    <hr>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">

        <div class ="container-fluid">
          <?php if($_SESSION['role'] != '5'){ ?>
            <button type="button" class="button" id="tambah_customer">New Customer</button>
          <?php } ?>
          <table id="tablenya" class="datatable responsive" style="width:100%">
            <thead>
              <tr>
                  <th>Bill Name</th>
                  <th>Bill Address</th>
                  <th>Ship Name</th>
                  <th>Ship Address</th>
                  <th>Option</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

          <!-- The Modal -->
          <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT CUSTOMER</h2>
              <form class="form cs_new" id="form_cs" data-id="" novalidate>
                <hr>

                <div class="form-group">
                  <h3>Bill Information</h3>
                </div>
                
                <div class="form-group b_nama">
                  <label for="b_nama">Nama : <span class="required">*</span></label>
                  <input type="text" class="form-control" name="customername" id="b_nama" required>
                </div>

                <div class="form-group b_alamat">
                  <label for="b_alamat">Address: <span class="required">*</span></label>
                  <textarea class="form-control" name="address" id="b_alamat" required></textarea>
                </div>

                <div class="form-group b_kota">
                  <label for="b_kota">Kota: <span class="required">*</span></label>
                  <input class="form-control" name="city" id="b_kota" required>
                </div>

                <div class="form-group b_negara">
                  <label for="b_negara">Negara: <span class="required">*</span></label>
                  <select name="country" id="b_negara" class="form-control">
                    <option value="" selected disabled>Pilih negara</option>
                    <option value="Afghanistan">Afghanistan</option>
                    <option value="Albania">Albania</option>
                    <option value="Algeria">Algeria</option>
                    <option value="American Samoa">American Samoa</option>
                    <option value="Andorra">Andorra</option>
                    <option value="Angola">Angola</option>
                    <option value="Anguilla">Anguilla</option>
                    <option value="Antarctica">Antarctica</option>
                    <option value="Antigua And Barbuda">Antigua And Barbuda</option>
                    <option value="Argentina">Argentina</option>
                    <option value="Armenia">Armenia</option>
                    <option value="Aruba">Aruba</option>
                    <option value="Australia">Australia</option>
                    <option value="Austria">Austria</option>
                    <option value="Azerbaijan">Azerbaijan</option>
                    <option value="Bahamas The">Bahamas The</option>
                    <option value="Bangladesh">Bangladesh</option>
                    <option value="Barbados">Barbados</option>
                    <option value="Belarus">Belarus</option>
                    <option value="Belgium">Belgium</option>
                    <option value="Belize">Belize</option>
                    <option value="Benin">Benin</option>
                    <option value="Bermuda">Bermuda</option>
                    <option value="Bhutan">Bhutan</option>
                    <option value="Bolivia">Bolivia</option>
                    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                    <option value="Botswana">Botswana</option>
                    <option value="Bouvet Island">Bouvet Island</option>
                    <option value="Brazil">Brazil</option>
                    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                    <option value="Brunei">Brunei</option>
                    <option value="Bulgaria">Bulgaria</option>
                    <option value="Burkina Faso">Burkina Faso</option>
                    <option value="Burundi">Burundi</option>
                    <option value="Cambodia">Cambodia</option>
                    <option value="Cameroon">Cameroon</option>
                    <option value="Canada">Canada</option>
                    <option value="Cape Verde">Cape Verde</option>
                    <option value="Cayman Islands">Cayman Islands</option>
                    <option value="Central African Republic">Central African Republic</option>
                    <option value="Chad">Chad</option>
                    <option value="Chile">Chile</option>
                    <option value="China">China</option>
                    <option value="Christmas Island">Christmas Island</option>
                    <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                    <option value="Colombia">Colombia</option>
                    <option value="Comoros">Comoros</option>
                    <option value="Republic Of The Congo">Republic Of The Congo</option>
                    <option value="Democratic Republic Of The Congo">Democratic Republic Of The Congo</option>
                    <option value="Cook Islands">Cook Islands</option>
                    <option value="Costa Rica">Costa Rica</option>
                    <option value="Cote D\'Ivoire (Ivory Coast)">Cote D\'Ivoire (Ivory Coast)</option>
                    <option value="Croatia (Hrvatska)">Croatia (Hrvatska)</option>
                    <option value="Cuba">Cuba</option>
                    <option value="Cyprus">Cyprus</option>
                    <option value="Czech Republic">Czech Republic</option>
                    <option value="Denmark">Denmark</option>
                    <option value="Djibouti">Djibouti</option>
                    <option value="Dominica">Dominica</option>
                    <option value="Dominican Republic">Dominican Republic</option>
                    <option value="East Timor">East Timor</option>
                    <option value="Ecuador">Ecuador</option>
                    <option value="Egypt">Egypt</option>
                    <option value="El Salvador">El Salvador</option>
                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                    <option value="Eritrea">Eritrea</option>
                    <option value="Estonia">Estonia</option>
                    <option value="Ethiopia">Ethiopia</option>
                    <option value="External Territories of Australia">External Territories of Australia</option>
                    <option value="Falkland Islands">Falkland Islands</option>
                    <option value="Faroe Islands">Faroe Islands</option>
                    <option value="Fiji Islands">Fiji Islands</option>
                    <option value="Finland">Finland</option>
                    <option value="France">France</option>
                    <option value="French Guiana">French Guiana</option>
                    <option value="French Polynesia">French Polynesia</option>
                    <option value="French Southern Territories">French Southern Territories</option>
                    <option value="Gabon">Gabon</option>
                    <option value="Gambia The">Gambia The</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Germany">Germany</option>
                    <option value="Ghana">Ghana</option>
                    <option value="Gibraltar">Gibraltar</option>
                    <option value="Greece">Greece</option>
                    <option value="Greenland">Greenland</option>
                    <option value="Grenada">Grenada</option>
                    <option value="Guadeloupe">Guadeloupe</option>
                    <option value="Guam">Guam</option>
                    <option value="Guatemala">Guatemala</option>
                    <option value="Guernsey and Alderney">Guernsey and Alderney</option>
                    <option value="Guinea-Bissau">Guinea-Bissau</option>
                    <option value="Guyana">Guyana</option>
                    <option value="Haiti">Haiti</option>
                    <option value="Heard and McDonald Islands">Heard and McDonald Islands</option>
                    <option value="Honduras">Honduras</option>
                    <option value="Hong Kong S.A.R.">Hong Kong S.A.R.</option>
                    <option value="Hungary">Hungary</option>
                    <option value="Iceland">Iceland</option>
                    <option value="India">India</option>
                    <option value="Indonesia">Indonesia</option>
                    <option value="Iran">Iran</option>
                    <option value="Iraq">Iraq</option>
                    <option value="Ireland">Ireland</option>
                    <option value="Israel">Israel</option>
                    <option value="Italy">Italy</option>
                    <option value="Jamaica">Jamaica</option>
                    <option value="Japan">Japan</option>
                    <option value="Jersey">Jersey</option>
                    <option value="Jordan">Jordan</option>
                    <option value="Kazakhstan">Kazakhstan</option>
                    <option value="Kenya">Kenya</option>
                    <option value="Kiribati">Kiribati</option>
                    <option value="Korea North">Korea North</option>
                    <option value="Korea South">Korea South</option>
                    <option value="Kuwait">Kuwait</option>
                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                    <option value="Laos">Laos</option>
                    <option value="Latvia">Latvia</option>
                    <option value="Lebanon">Lebanon</option>
                    <option value="Lesotho">Lesotho</option>
                    <option value="Liberia">Liberia</option>
                    <option value="Libya">Libya</option>
                    <option value="Liechtenstein">Liechtenstein</option>
                    <option value="Lithuania">Lithuania</option>
                    <option value="Luxembourg">Luxembourg</option>
                    <option value="Macau S.A.R.">Macau S.A.R.</option>
                    <option value="Macedonia">Macedonia</option>
                    <option value="Madagascar">Madagascar</option>
                    <option value="Malawi">Malawi</option>
                    <option value="Malaysia">Malaysia</option>
                    <option value="Maldives">Maldives</option>
                    <option value="Mali">Mali</option>
                    <option value="Malta">Malta</option>
                    <option value="Man (Isle of)">Man (Isle of)</option>
                    <option value="Marshall Islands">Marshall Islands</option>
                    <option value="Martinique">Martinique</option>
                    <option value="Mauritania">Mauritania</option>
                    <option value="Mauritius">Mauritius</option>
                    <option value="Mayotte">Mayotte</option>
                    <option value="Mexico">Mexico</option>
                    <option value="Micronesia">Micronesia</option>
                    <option value="Moldova">Moldova</option>
                    <option value="Monaco">Monaco</option>
                    <option value="Mongolia">Mongolia</option>
                    <option value="Montserrat">Montserrat</option>
                    <option value="Morocco">Morocco</option>
                    <option value="Mozambique">Mozambique</option>
                    <option value="Myanmar">Myanmar</option>
                    <option value="Namibia">Namibia</option>
                    <option value="Nauru">Nauru</option>
                    <option value="Nepal">Nepal</option>
                    <option value="Netherlands Antilles">Netherlands Antilles</option>
                    <option value="Netherlands The">Netherlands The</option>
                    <option value="New Caledonia">New Caledonia</option>
                    <option value="New Zealand">New Zealand</option>
                    <option value="Nicaragua">Nicaragua</option>
                    <option value="Niger">Niger</option>
                    <option value="Nigeria">Nigeria</option>
                    <option value="Niue">Niue</option>
                    <option value="Norfolk Island">Norfolk Island</option>
                    <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                    <option value="Norway">Norway</option>
                    <option value="Oman">Oman</option>
                    <option value="Pakistan">Pakistan</option>
                    <option value="Palau">Palau</option>
                    <option value="Palestinian Territory Occupied">Palestinian Territory Occupied</option>
                    <option value="Papua new Guinea">Papua new Guinea</option>
                    <option value="Paraguay">Paraguay</option>
                    <option value="Peru">Peru</option>
                    <option value="Philippines">Philippines</option>
                    <option value="Pitcairn Island">Pitcairn Island</option>
                    <option value="Poland">Poland</option>
                    <option value="Portugal">Portugal</option>
                    <option value="Puerto Rico">Puerto Rico</option>
                    <option value="Qatar">Qatar</option>
                    <option value="Reunion">Reunion</option>
                    <option value="Romania">Romania</option>
                    <option value="Russia">Russia</option>
                    <option value="Rwanda">Rwanda</option>
                    <option value="Saint Helena">Saint Helena</option>
                    <option value="Saint Kitts And Nevis">Saint Kitts And Nevis</option>
                    <option value="Saint Lucia">Saint Lucia</option>
                    <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                    <option value="Saint Vincent And The Grenadines">Saint Vincent And The Grenadines</option>
                    <option value="Samoa">Samoa</option>
                    <option value="San Marino">San Marino</option>
                    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                    <option value="Saudi Arabia">Saudi Arabia</option>
                    <option value="Senegal">Senegal</option>
                    <option value="Serbia">Serbia</option>
                    <option value="Seychelles">Seychelles</option>
                    <option value="Sierra Leone">Sierra Leone</option>
                    <option value="Singapore">Singapore</option>
                    <option value="Slovakia">Slovakia</option>
                    <option value="Slovenia">Slovenia</option>
                    <option value="Smaller Territories of the UK">Smaller Territories of the UK</option>
                    <option value="Solomon Islands">Solomon Islands</option>
                    <option value="Somalia">Somalia</option>
                    <option value="South Africa">South Africa</option>
                    <option value="South Georgia">South Georgia</option>
                    <option value="South Sudan">South Sudan</option>
                    <option value="Spain">Spain</option>
                    <option value="Sri Lanka">Sri Lanka</option>
                    <option value="Sudan">Sudan</option>
                    <option value="Suriname">Suriname</option>
                    <option value="Svalbard And Jan Mayen Islands">Svalbard And Jan Mayen Islands</option>
                    <option value="Swaziland">Swaziland</option>
                    <option value="Sweden">Sweden</option>
                    <option value="Switzerland">Switzerland</option>
                    <option value="Syria">Syria</option>
                    <option value="Taiwan">Taiwan</option>
                    <option value="Tajikistan">Tajikistan</option>
                    <option value="Tanzania">Tanzania</option>
                    <option value="Thailand">Thailand</option>
                    <option value="Togo">Togo</option>
                    <option value="Tokelau">Tokelau</option>
                    <option value="Tonga">Tonga</option>
                    <option value="Trinidad And Tobago">Trinidad And Tobago</option>
                    <option value="Tunisia">Tunisia</option>
                    <option value="Turkey">Turkey</option>
                    <option value="Turkmenistan">Turkmenistan</option>
                    <option value="Turks And Caicos Islands">Turks And Caicos Islands</option>
                    <option value="Tuvalu">Tuvalu</option>
                    <option value="Uganda">Uganda</option>
                    <option value="Ukraine">Ukraine</option>
                    <option value="United Arab Emirates">United Arab Emirates</option>
                    <option value="United Kingdom">United Kingdom</option>
                    <option value="United States">United States</option>
                    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                    <option value="Uruguay">Uruguay</option>
                    <option value="Uzbekistan">Uzbekistan</option>
                    <option value="Vanuatu">Vanuatu</option>
                    <option value="Vatican City State (Holy See)">Vatican City State (Holy See)</option>
                    <option value="Venezuela">Venezuela</option>
                    <option value="Vietnam">Vietnam</option>
                    <option value="Virgin Islands (British)">Virgin Islands (British)</option>
                    <option value="Virgin Islands (US)">Virgin Islands (US)</option>
                    <option value="Wallis And Futuna Islands">Wallis And Futuna Islands</option>
                    <option value="Western Sahara">Western Sahara</option>
                    <option value="Yemen">Yemen</option>
                    <option value="Yugoslavia">Yugoslavia</option>
                    <option value="Zambia">Zambia</option>
                    <option value="Zimbabwe">Zimbabwe</option>
                  </select>
                </div>

                <div class="form-group b_provinsi">
                  <label for="b_provinsi">Provinsi: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="province" id="b_provinsi" required>
                </div>

                <div class="form-group b_kodepos">
                  <label for="b_kodepos">Kode Pos: <span class="required">*</span></label>
                  <input class="form-control" name="postalcode" id="b_kodepos" required>
                </div>

                <div class="form-group b_telp">
                  <label for="b_telp">No Telepon: <span class="required">*</span></label>
                  <input type="number" min="0" maxlength="14" class="form-control" name="phone" id="b_telp" required>
                </div>

                <hr>

                <div class="form-group">
                  <h3>Ship Information</h3>
                </div>
                
                <div class="form-group s_nama">
                  <label for="s_nama">Nama :</label>
                  <input type="text" class="form-control" name="sname" id="s_nama">
                </div>

                <div class="form-group s_alamat">
                  <label for="s_alamat">Address:</label>
                  <textarea class="form-control" name="saddress" id="s_alamat"></textarea>
                </div>

                <div class="form-group s_kota">
                  <label for="s_kota">Kota:</label>
                  <input class="form-control" name="scity" id="s_kota">
                </div>

                <div class="form-group s_negara">
                  <label for="s_negara">Negara:</label>
                  <select name="scountry" id="s_negara" class="form-control">
                    <option value="" selected disabled>Pilih negara</option>
                    <option value="Afghanistan">Afghanistan</option>
                    <option value="Albania">Albania</option>
                    <option value="Algeria">Algeria</option>
                    <option value="American Samoa">American Samoa</option>
                    <option value="Andorra">Andorra</option>
                    <option value="Angola">Angola</option>
                    <option value="Anguilla">Anguilla</option>
                    <option value="Antarctica">Antarctica</option>
                    <option value="Antigua And Barbuda">Antigua And Barbuda</option>
                    <option value="Argentina">Argentina</option>
                    <option value="Armenia">Armenia</option>
                    <option value="Aruba">Aruba</option>
                    <option value="Australia">Australia</option>
                    <option value="Austria">Austria</option>
                    <option value="Azerbaijan">Azerbaijan</option>
                    <option value="Bahamas The">Bahamas The</option>
                    <option value="Bangladesh">Bangladesh</option>
                    <option value="Barbados">Barbados</option>
                    <option value="Belarus">Belarus</option>
                    <option value="Belgium">Belgium</option>
                    <option value="Belize">Belize</option>
                    <option value="Benin">Benin</option>
                    <option value="Bermuda">Bermuda</option>
                    <option value="Bhutan">Bhutan</option>
                    <option value="Bolivia">Bolivia</option>
                    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                    <option value="Botswana">Botswana</option>
                    <option value="Bouvet Island">Bouvet Island</option>
                    <option value="Brazil">Brazil</option>
                    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                    <option value="Brunei">Brunei</option>
                    <option value="Bulgaria">Bulgaria</option>
                    <option value="Burkina Faso">Burkina Faso</option>
                    <option value="Burundi">Burundi</option>
                    <option value="Cambodia">Cambodia</option>
                    <option value="Cameroon">Cameroon</option>
                    <option value="Canada">Canada</option>
                    <option value="Cape Verde">Cape Verde</option>
                    <option value="Cayman Islands">Cayman Islands</option>
                    <option value="Central African Republic">Central African Republic</option>
                    <option value="Chad">Chad</option>
                    <option value="Chile">Chile</option>
                    <option value="China">China</option>
                    <option value="Christmas Island">Christmas Island</option>
                    <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                    <option value="Colombia">Colombia</option>
                    <option value="Comoros">Comoros</option>
                    <option value="Republic Of The Congo">Republic Of The Congo</option>
                    <option value="Democratic Republic Of The Congo">Democratic Republic Of The Congo</option>
                    <option value="Cook Islands">Cook Islands</option>
                    <option value="Costa Rica">Costa Rica</option>
                    <option value="Cote D\'Ivoire (Ivory Coast)">Cote D\'Ivoire (Ivory Coast)</option>
                    <option value="Croatia (Hrvatska)">Croatia (Hrvatska)</option>
                    <option value="Cuba">Cuba</option>
                    <option value="Cyprus">Cyprus</option>
                    <option value="Czech Republic">Czech Republic</option>
                    <option value="Denmark">Denmark</option>
                    <option value="Djibouti">Djibouti</option>
                    <option value="Dominica">Dominica</option>
                    <option value="Dominican Republic">Dominican Republic</option>
                    <option value="East Timor">East Timor</option>
                    <option value="Ecuador">Ecuador</option>
                    <option value="Egypt">Egypt</option>
                    <option value="El Salvador">El Salvador</option>
                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                    <option value="Eritrea">Eritrea</option>
                    <option value="Estonia">Estonia</option>
                    <option value="Ethiopia">Ethiopia</option>
                    <option value="External Territories of Australia">External Territories of Australia</option>
                    <option value="Falkland Islands">Falkland Islands</option>
                    <option value="Faroe Islands">Faroe Islands</option>
                    <option value="Fiji Islands">Fiji Islands</option>
                    <option value="Finland">Finland</option>
                    <option value="France">France</option>
                    <option value="French Guiana">French Guiana</option>
                    <option value="French Polynesia">French Polynesia</option>
                    <option value="French Southern Territories">French Southern Territories</option>
                    <option value="Gabon">Gabon</option>
                    <option value="Gambia The">Gambia The</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Germany">Germany</option>
                    <option value="Ghana">Ghana</option>
                    <option value="Gibraltar">Gibraltar</option>
                    <option value="Greece">Greece</option>
                    <option value="Greenland">Greenland</option>
                    <option value="Grenada">Grenada</option>
                    <option value="Guadeloupe">Guadeloupe</option>
                    <option value="Guam">Guam</option>
                    <option value="Guatemala">Guatemala</option>
                    <option value="Guernsey and Alderney">Guernsey and Alderney</option>
                    <option value="Guinea-Bissau">Guinea-Bissau</option>
                    <option value="Guyana">Guyana</option>
                    <option value="Haiti">Haiti</option>
                    <option value="Heard and McDonald Islands">Heard and McDonald Islands</option>
                    <option value="Honduras">Honduras</option>
                    <option value="Hong Kong S.A.R.">Hong Kong S.A.R.</option>
                    <option value="Hungary">Hungary</option>
                    <option value="Iceland">Iceland</option>
                    <option value="India">India</option>
                    <option value="Indonesia">Indonesia</option>
                    <option value="Iran">Iran</option>
                    <option value="Iraq">Iraq</option>
                    <option value="Ireland">Ireland</option>
                    <option value="Israel">Israel</option>
                    <option value="Italy">Italy</option>
                    <option value="Jamaica">Jamaica</option>
                    <option value="Japan">Japan</option>
                    <option value="Jersey">Jersey</option>
                    <option value="Jordan">Jordan</option>
                    <option value="Kazakhstan">Kazakhstan</option>
                    <option value="Kenya">Kenya</option>
                    <option value="Kiribati">Kiribati</option>
                    <option value="Korea North">Korea North</option>
                    <option value="Korea South">Korea South</option>
                    <option value="Kuwait">Kuwait</option>
                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                    <option value="Laos">Laos</option>
                    <option value="Latvia">Latvia</option>
                    <option value="Lebanon">Lebanon</option>
                    <option value="Lesotho">Lesotho</option>
                    <option value="Liberia">Liberia</option>
                    <option value="Libya">Libya</option>
                    <option value="Liechtenstein">Liechtenstein</option>
                    <option value="Lithuania">Lithuania</option>
                    <option value="Luxembourg">Luxembourg</option>
                    <option value="Macau S.A.R.">Macau S.A.R.</option>
                    <option value="Macedonia">Macedonia</option>
                    <option value="Madagascar">Madagascar</option>
                    <option value="Malawi">Malawi</option>
                    <option value="Malaysia">Malaysia</option>
                    <option value="Maldives">Maldives</option>
                    <option value="Mali">Mali</option>
                    <option value="Malta">Malta</option>
                    <option value="Man (Isle of)">Man (Isle of)</option>
                    <option value="Marshall Islands">Marshall Islands</option>
                    <option value="Martinique">Martinique</option>
                    <option value="Mauritania">Mauritania</option>
                    <option value="Mauritius">Mauritius</option>
                    <option value="Mayotte">Mayotte</option>
                    <option value="Mexico">Mexico</option>
                    <option value="Micronesia">Micronesia</option>
                    <option value="Moldova">Moldova</option>
                    <option value="Monaco">Monaco</option>
                    <option value="Mongolia">Mongolia</option>
                    <option value="Montserrat">Montserrat</option>
                    <option value="Morocco">Morocco</option>
                    <option value="Mozambique">Mozambique</option>
                    <option value="Myanmar">Myanmar</option>
                    <option value="Namibia">Namibia</option>
                    <option value="Nauru">Nauru</option>
                    <option value="Nepal">Nepal</option>
                    <option value="Netherlands Antilles">Netherlands Antilles</option>
                    <option value="Netherlands The">Netherlands The</option>
                    <option value="New Caledonia">New Caledonia</option>
                    <option value="New Zealand">New Zealand</option>
                    <option value="Nicaragua">Nicaragua</option>
                    <option value="Niger">Niger</option>
                    <option value="Nigeria">Nigeria</option>
                    <option value="Niue">Niue</option>
                    <option value="Norfolk Island">Norfolk Island</option>
                    <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                    <option value="Norway">Norway</option>
                    <option value="Oman">Oman</option>
                    <option value="Pakistan">Pakistan</option>
                    <option value="Palau">Palau</option>
                    <option value="Palestinian Territory Occupied">Palestinian Territory Occupied</option>
                    <option value="Papua new Guinea">Papua new Guinea</option>
                    <option value="Paraguay">Paraguay</option>
                    <option value="Peru">Peru</option>
                    <option value="Philippines">Philippines</option>
                    <option value="Pitcairn Island">Pitcairn Island</option>
                    <option value="Poland">Poland</option>
                    <option value="Portugal">Portugal</option>
                    <option value="Puerto Rico">Puerto Rico</option>
                    <option value="Qatar">Qatar</option>
                    <option value="Reunion">Reunion</option>
                    <option value="Romania">Romania</option>
                    <option value="Russia">Russia</option>
                    <option value="Rwanda">Rwanda</option>
                    <option value="Saint Helena">Saint Helena</option>
                    <option value="Saint Kitts And Nevis">Saint Kitts And Nevis</option>
                    <option value="Saint Lucia">Saint Lucia</option>
                    <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                    <option value="Saint Vincent And The Grenadines">Saint Vincent And The Grenadines</option>
                    <option value="Samoa">Samoa</option>
                    <option value="San Marino">San Marino</option>
                    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                    <option value="Saudi Arabia">Saudi Arabia</option>
                    <option value="Senegal">Senegal</option>
                    <option value="Serbia">Serbia</option>
                    <option value="Seychelles">Seychelles</option>
                    <option value="Sierra Leone">Sierra Leone</option>
                    <option value="Singapore">Singapore</option>
                    <option value="Slovakia">Slovakia</option>
                    <option value="Slovenia">Slovenia</option>
                    <option value="Smaller Territories of the UK">Smaller Territories of the UK</option>
                    <option value="Solomon Islands">Solomon Islands</option>
                    <option value="Somalia">Somalia</option>
                    <option value="South Africa">South Africa</option>
                    <option value="South Georgia">South Georgia</option>
                    <option value="South Sudan">South Sudan</option>
                    <option value="Spain">Spain</option>
                    <option value="Sri Lanka">Sri Lanka</option>
                    <option value="Sudan">Sudan</option>
                    <option value="Suriname">Suriname</option>
                    <option value="Svalbard And Jan Mayen Islands">Svalbard And Jan Mayen Islands</option>
                    <option value="Swaziland">Swaziland</option>
                    <option value="Sweden">Sweden</option>
                    <option value="Switzerland">Switzerland</option>
                    <option value="Syria">Syria</option>
                    <option value="Taiwan">Taiwan</option>
                    <option value="Tajikistan">Tajikistan</option>
                    <option value="Tanzania">Tanzania</option>
                    <option value="Thailand">Thailand</option>
                    <option value="Togo">Togo</option>
                    <option value="Tokelau">Tokelau</option>
                    <option value="Tonga">Tonga</option>
                    <option value="Trinidad And Tobago">Trinidad And Tobago</option>
                    <option value="Tunisia">Tunisia</option>
                    <option value="Turkey">Turkey</option>
                    <option value="Turkmenistan">Turkmenistan</option>
                    <option value="Turks And Caicos Islands">Turks And Caicos Islands</option>
                    <option value="Tuvalu">Tuvalu</option>
                    <option value="Uganda">Uganda</option>
                    <option value="Ukraine">Ukraine</option>
                    <option value="United Arab Emirates">United Arab Emirates</option>
                    <option value="United Kingdom">United Kingdom</option>
                    <option value="United States">United States</option>
                    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                    <option value="Uruguay">Uruguay</option>
                    <option value="Uzbekistan">Uzbekistan</option>
                    <option value="Vanuatu">Vanuatu</option>
                    <option value="Vatican City State (Holy See)">Vatican City State (Holy See)</option>
                    <option value="Venezuela">Venezuela</option>
                    <option value="Vietnam">Vietnam</option>
                    <option value="Virgin Islands (British)">Virgin Islands (British)</option>
                    <option value="Virgin Islands (US)">Virgin Islands (US)</option>
                    <option value="Wallis And Futuna Islands">Wallis And Futuna Islands</option>
                    <option value="Western Sahara">Western Sahara</option>
                    <option value="Yemen">Yemen</option>
                    <option value="Yugoslavia">Yugoslavia</option>
                    <option value="Zambia">Zambia</option>
                    <option value="Zimbabwe">Zimbabwe</option>
                  </select>
                </div>

                <div class="form-group s_provinsi">
                  <label for="s_provinsi">Provinsi:</label>
                  <input type="text" class="form-control" name="sprovince" id="s_provinsi">
                </div>

                <div class="form-group s_kodepos">
                  <label for="s_kodepos">Kode Pos:</label>
                  <input class="form-control" name="spostalcode" id="s_kodepos">
                </div>

                <div class="form-group s_telp">
                  <label for="s_telp">No Telepon:</label>
                  <input type="number" min="0" maxlength="14" class="form-control" name="sphone" id="s_telp">
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="saving">Simpan</button>
                  <input type="button" class="lightbox_close" value="Batal">
                </div>
              </form>
            </div>

          </div>

          <noscript id="noscript_container">
            <div id="noscript" class="error">
              <p>JavaScript support is needed to use this page.</p>
            </div>
          </noscript>

          <div id="message_container">
            <div id="message" class="success">
              <p>This is a success message.</p>
            </div>
          </div>

          <div id="loading_container">
            <div id="loading_container2">
              <div id="loading_container3">
                <div id="loading_container4">
                  Loading, please wait...
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "preorder"){ ?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo ucfirst(htmlspecialchars($_GET["page"])); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo ucfirst(htmlspecialchars($_GET["page"])); ?></li>
      </ol>
    </section>

    <hr>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class ="container-fluid">
          <?php if($_SESSION['role'] != '5'){ ?>
            <button type="button" class="button" id="add_inputPO">Create New</button>
            <button type="button" class="button" id="add_item_po">Add Item PO</button>
          <?php } ?>
          <table id="tablenya" class="datatable" style="width:100%">
            <caption>
              <div id="widthSortby" class="input-group">
                <span class="input-group-addon">Sort by </span>
                <select id="sortby" class="form-control"></select>
                <span class="input-group-btn">
                  <button type="button" id="LoadData" class="btn btn-default">View</button>
                </span>
              </div>
            </caption>
            <thead>
              <tr>
                  <th>Diterbitkan</th>
                  <th>Customer</th>
                  <th>Estimasi</th>
                  <th>Company</th>
                  <th>Order Grade</th>
                  <th>No PO</th>
                  <th>No SO</th>
                  <th>Nama Barang</th>
                  <th>Jenis Item</th>
                  <th>Ukuran</th>
                  <th>Merk</th>
                  <th>Type</th>
                  <th>Uk. Bahan Baku</th>
                  <th>Qty</th>
                  <th>Satuan</th>
                  <th>Kor</th>
                  <th>Line</th>
                  <th>Qty Bahan Baku</th>
                  <th>Gulungan</th>
                  <th>Bahan</th>
                  <th>Porporasi</th>
                  <th>Isi ROLL/PCS</th>
                  <th>Harga</th>
                  <th>Jumlah</th>
                  <th>PPN</th>
                  <th>TOTAL</th>
                  <th>Catatan</th>
                  <th>Sources</th>
                  <th>Total Ongkir</th>
                  <th>Diinput</th>
                  <th>Option</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th class="text-right" style="font-weight: bold">Total Amount :</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
              </tr>
            </tfoot>
          </table>

          <!-- The Modal -->
          <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT PO</h2>
              <form class="form add" id="form_inputPO" data-id="" novalidate>

                <div class="form-group company">
                  <label for="company">Company: <span class="required">*</span></label>
                  <select class="form-control" name="companyid" id="company" required></select>
                </div>

                <div class="form-group customer">
                  <label for="customer">Customer: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="customer" id="customer" required>
                  <input type="hidden" name="customerid" id="id_customer" value="">
                  <input type="hidden" name="fkid" id="fkid" value="">
                  <input type="hidden" name="poid" id="poid" value="">
                </div>

                <div class="form-group po_date">
                  <label for="PO Date">Tgl PO: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="po_date" id="po_date" value="<?php echo date('Y-m-d'); ?>" required>
                </div>

                <div class="form-group po_customer">
                  <label for="PO Customer">No PO: </label>
                  <input type="text" class="form-control" name="po_customer" id="po_customer" value="">
                </div>

                <div class="form-group order_grade">
                  <label for="Order Grade">Order Grade: <span class="required">*</span></label>
                  <select class="form-control" name="order_grade" id="order_grade" required>
                    <option value="0" selected>Reguler</option>
                    <option value="1">Spesial</option>
                  </select>
                </div>

                <hr class='header-item'>
                <p><button type="button" class="btn btn-primary tambah_barang">Tambah barang</button></p>

                <div class="form-group source">
                  <label for="source">Sources: <span class="required">*</span></label>
                  <select class="form-control" name="data[sources][]" id="source" id_source ="1" required>
                    <option selected disabled>Pilih sources</option>
                    <option value="1">Internal</option>
                    <option value="2">Subcont</option>
                    <option value="3">In Stock</option>
                  </select>
                </div>
                
                <div class="form-group etc1-class-1" style="display:none">
                  <label for="Subcont-1">Subcont kepada: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[etc1][]" id="etc1-input-1" required>
                </div>

                <div class="form-group etc2-class-1" style="display:none">
                  <label for="Estimasi">Estimasi: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="data[etc2][]" id="etc2-input" required>
                </div>

                <div class="form-group item">
                  <label for="Item">Nama Barang: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[item][]" id="item" value="" required>
                </div>

                <div class="form-group detail">
                  <label STYLE="COLOR:BLUE" for="detail">Jenis Barang: <span class="required">*</span></label>
                  <select style="background-color: #F0FFFF" class="form-control" name="data[detail][]" id="detail" required></select>
                </div>

                <div class="form-group merk" style="display:none">
                  <label for="merk">Merk: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[merk][]" id="merk" value="" required>
                </div>

                <div class="form-group type" style="display:none">
                  <label for="type">Type: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[type][]" id="type" value="" required>
                </div>

                <div class="form-group size" style="display:none">
                  <label for="Size">Size: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[size][]" id="size" value="" required>
                </div>

                <div class="form-group uk_bahan_baku" style="display:none">
                  <label for="sbaku">Uk. Bahan baku: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[uk_bahan_baku][]" id="uk_bahan_baku" required>
                </div>

                <div class="form-group qore" style="display:none">
                  <label for="Qore">Qore: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[qore][]" id="qore" required>
                </div>

                <div class="form-group lin" style="display:none">
                  <label for="lin">Line: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[lin][]" id="lin" required>
                </div>

                <div class="form-group qty_bahan_baku" style="display:none">
                  <label for="qty_bahan_baku">QTY Bahan baku: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[qty_bahan_baku][]" id="qty_bahan_baku" required>
                </div>

                <div class="form-group roll" style="display:none">
                  <label for="roll">Gulungan:</label>
                    <select class="form-control" name="data[roll][]" id="roll">
                      <option selected>Select roll</option>
                      <option value="FI">FI</option>
                      <option value="FO">FO</option>
                      <option value="LIPAT">LIPAT</option>
                      <option value="SHEET">SHEET</option>
                    </select>
                </div>

                <div class="form-group ingredient" style="display:none">
                  <label for="ingredient">Bahan: </label>
                  <input type="text" class="form-control" name="data[ingredient][]" id="ingredient" value="">
                </div>

                <div class="form-group porporasi" style="display:none">
                  <label>Porporasi:</label>
                  <select class="form-control" id="porporasi" name="data[porporasi][]">
                    <option selected>Pilih Porporasi</option>
                    <option value="1">YA</option>
                    <option value="0">TIDAK</option>
                    </select>
                </div>

                <div class="form-group unit" style="display:none">
                  <label STYLE="COLOR:BLUE" for="Unit">Unit: <span class="required">*</span></label>
                    <select style="background-color: #F0FFFF" class="form-control" name="data[unit][]" id="unit">
                      <option value="" selected>Pilih satuan</option>
                      <option value="PCS">PCS</option>
                      <option value="ROLL">ROLL</option>
                      <option value="PAK">PACK</option>
                      <option value="CM">CM</option>
                      <option value="MM">MM</option>
                      <option value="METER">METER</option>
                      <option value="DUSH">DUSH</option>
                      <option value="BOTOL">BOTOL</option>
                      <option value="UNIT">UNIT</option>
                      <option value="ONS">ONS</option>
                      <option value="KG">KG</option>
                      <option value="LITER">LITER</option>
                    </select>
                </div>
        
                <div class="form-group qty">
                  <label for="Qty">Qty: <span class="required">*</span></label>
                  <input type="number" min="1" class="form-control" name="data[qty][]" id="qty" value="0" placeholder="0" required>
                </div>

                <div class="form-group volume" style="display:none">
                  <label for="volume">Isi Roll/Pcs: <span class="required">*</span></label>
                  <input type="number" min="0" class="form-control" name="data[volume][]" id="volume" value="" placeholder="1" required>
                </div>

                <div class="form-group price">
                  <label for="Price">Harga: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[price][]" id="price" value="0" placeholder="0" required>
                </div>

                <div class="form-group annotation">
                  <label for="Annotation">Catatan:</label>
                  <input type="text" class="form-control" name="data[annotation][]" id="annotation" value="">
                </div>

                <div id="looping_barang"></div>
                
                <hr class='footer-item'>

                <div class="form-group ppns">
                  <label for="sel1">PPN: <span class="required">*</span></label></label>
                  <select class="form-control" name="tax" id="ppns" required>
                    <option selected disabled>Menggunakan PPN 11% ?</option>
                    <option value="0">Tidak</option>
                    <option value="1">Ya</option>
                  </select>
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="saving">Simpan</button>
                  <input type="button" class="lightbox_close" value="Batal">
                </div>
                <input type="hidden" name="id_wo" id="id_wo" value="">
              </form>
            </div>
          </div>

          <!-- Modal Ongkir -->
          <div id="OngkirModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close ongkirbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT ONGKIR</h2>
              <form class="form add" id="form_inputOngkir" data-id="" novalidate>

                <div class="form-group surat_jalan">
                  <label for="surat_jalan">No Surat Jalan: <span class="required">*</span></label>
                  <select class="form-control" name="surat_jalan" id="surat_jalan" required></select>
                </div>

                <div class="form-group ongkos_kirim">
                  <label for="ongkos_kirim">Biaya Kirim: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="cost" id="ongkos_kirim" required>
                </div>

                <div class="form-group ekspedisi">
                  <label for="ekspedisi">Ekspedisi: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="ekspedisi" id="ekspedisi" required>
                </div>

                <div class="form-group ppns">
                  <label for="sel1">Unit: <span class="required">*</span></label></label>
                  <select class="form-control" name="uom" id="uom" required>
                    <option value="" selected>Pilih satuan</option>
                    <option value="PCS">PCS</option>
                    <option value="ROLL">ROLL</option>
                    <option value="PAK">PACK</option>
                    <option value="CM">CM</option>
                    <option value="MM">MM</option>
                    <option value="METER">METER</option>
                    <option value="DUSH">DUSH</option>
                    <option value="BOTOL">BOTOL</option>
                    <option value="UNIT">UNIT</option>
                    <option value="ONS">ONS</option>
                    <option value="KG">KG</option>
                    <option value="LITER">LITER</option>
                  </select>
                </div>

                <div class="form-group jml">
                  <label for="jml">Jumlah: <span class="required">*</span></label>
                  <input type="number" min="0" class="form-control" name="jml" id="jml" required>
                </div>
        
                <div class="button_container" style="text-align: center">
                  <button type="submit" class="saving">Simpan</button>
                  <input type="button" class="ongkirbox_close" value="Batal">
                </div>
              </form>
            </div>
          </div>

          <!-- Modal Ongkir -->
          <div id="PeriodeResult" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close periode_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT PERIODE</h2>
              <form class="form add" id="form_periode" data-id="" novalidate>

                <div class="form-group">
                  <label>Dari Tanggal: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="startdate" id="startdate" required>
                </div>

                <div class="form-group">
                  <label>Sampai: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="enddate" id="enddate" required>
                </div>
        
                <div class="button_container" style="text-align: center">
                  <button type="submit" class="lihat">View</button>
                  <input type="button" class="periode_close" value="Cancel">
                  <input type="hidden" id="report" name="report" value="periode">
                </div>
              </form>
            </div>
          </div>

          <noscript id="noscript_container">
            <div id="noscript" class="error">
              <p>JavaScript support is needed to use this page.</p>
            </div>
          </noscript>

          <div id="message_container">
            <div id="message" class="success">
              <p>This is a success message.</p>
            </div>
          </div>

          <div id="loading_container">
            <div id="loading_container2">
              <div id="loading_container3">
                <div id="loading_container4">
                  Loading, please wait...
                </div>
              </div>
            </div>
          </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "workorder"){ ?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo ucfirst(htmlspecialchars($_GET["page"])); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo ucfirst(htmlspecialchars($_GET["page"])); ?></li>
      </ol>
    </section>

    <hr>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class ="container-fluid">
          <button type="button" class="button backWO" style="display: none">Kembali</button>
          <table id="tablenya" class="datatable nowrap" style="width:100%">
            <caption>
              <div id="widthSortby" class="input-group">
                <span class="input-group-addon">Sort by </span>
                <select id="sortby" class="form-control"></select>
                <span class="input-group-btn">
                  <button type="button" id="LoadData" class="btn btn-default">View</button>
                </span>
              </div>
            </caption>
            <thead>
              <tr>
                  <th>Diterbitkan</th>
                  <th>Tenggat Waktu</th>
                  <th>Customer</th>
                  <th>No PO</th>
                  <th>No SO</th>
                  <th>Nama Barang</th>
                  <th>Ukuran</th>
                  <th>Kor</th>
                  <th>Line</th>
                  <th>Roll</th>
                  <th>Bahan</th>
                  <th>Porporasi</th>
                  <th>Qty</th>
                  <th>Unit</th>
                  <th>Isi ROLL/PCS</th>
                  <th>Catatan</th>
                  <th>Uk Bahan Baku</th>
                  <th>Qty Bahan Baku</th>
                  <th>Sources</th>
                  <th>Order Status</th>
                  <th>Diinput</th>
                  <th>Option</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
      
          <!-- The Modal -->
          <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT WO</h2>
              <form class="form add" id="form_inputWO" data-id="" novalidate>

                <div class="form-group po_date" style="display: none">
                  <label for="po_date">Tgl PO:</label>
                  <input type="date" class="form-control" name="po_date" id="po_date" required readonly>
                </div>

                <div class="form-group customer" style="display: none">
                  <label for="customer">Customer:</label>
                  <input type="text" class="form-control" name="customer" id="customer" required readonly>
                </div>

                <div class="form-group no_po" style="display: none">
                  <label for="po_customer">No PO:</label>
                  <input type="text" class="form-control" name="po_customer" id="po_customer" readonly>
                </div>

                <div class="form-group no_spk" style="display: none">
                  <label for="no_spk">No SPK/SO:</label>
                  <input type="text" class="form-control" name="no_spk" id="no_spk" required readonly>
                </div>

                <div class="form-group spk_date" style="display: none">
                  <label for="spk_date">Tgl SPK:</label>
                  <input type="date" class="form-control" name="spk_date" id="spk_date" required>
                </div>

                <div class="form-group order_status" style="display: none">
                  <label for="Order status">Order Status: <span class="required">*</span></label>
                  <select class="form-control" name="order_status" id="order_status" required>
                    <option selected disabled>Pilih status</option>
                     <option value="16">Input PO</option>
                    <option value="15">Proses Sample</option>
                    <option value="14">Reture</option> 
                    <option value="13">Proses Sliting</option> 
                    <option value="12">Proses ACC</option> 
                    <option value="11">Proses Toyobo</option> 
                    <option value="10">Proses Film</option>
                    <option value="9">Proses Bahan Baku</option>
                    <option value="8">Proses Cetak</option>
                    <option value="7">Antri Cetak</option>
                    <option value="6">Antri Sliting</option>
                    <option value="5">Pembuatan Pisau</option>
                    <option value="4">Cetak SPK</option>
                    <option value="3">Packing</option>
                    <option value="3">Delivery</option>
                  </select>
                </div>


                <div class="button_container" style="text-align: center">
                  <button type="submit" class="saving">Submit</button>
                  <input type="button" class="lightbox_close" value="Cancel">
                </div>
              </form>
            </div>
          </div>

          <!-- The Modal -->
          <div id="myModal2" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">PRATINJAU PRINT</h2>
              <form class="form add" id="form_print" novalidate>

                <hr>
                <p style="font-weight: bold">DITERBITKAN</p>
                
                <div class="form-group">
                  <label for="tgl">Tanggal: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="spk_date" id="tgl" required>
                </div>

                <hr>
                <p style="font-weight: bold">RINCIAN PRODUKSI</p>
                <div class="form-group">
                  <label for="pcus">No PO: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="po_customer" id="pcus" readonly>
                </div>
                <div class="form-group">
                  <label for="customer">Customer: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="customer" id="custom" required readonly>
                </div>

                <div class="form-group">
                  <label for="no_spk">No SO: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="no_spk" id="nospk" required readonly>
                </div>
        
                <div class="form-group">
                  <label for="keterangan">Keterangan:</label>
                  <input type="text" class="form-control" name="annotation" id="keterangan">
                </div>
        
                <div class="form-group">
                  <label for="size_label">Ukuran:<span class="required">*</span></label>
                  <input type="text" class="form-control" name="size" id="size_label" required readonly>
                </div>
        
                <div class="form-group">
                  <label for="size_baku">Uk. Bahan baku:</label>
                  <input type="text" class="form-control" name="uk_bahan_baku" id="size_baku" required readonly>
                </div>
        
                <div class="form-group">
                  <label for="bahan">Bahan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="ingredient" id="bahan" required readonly>
                </div>

                <div class="form-group porporasi">
                  <label>Porporasi: <span class="required">*</span></label>
                  <input type="text" class="form-control" id="porporasi" name="porporasi" readonly>
                </div>
        
                <div class="form-group">
                  <label for="gulungan">Gulungan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="roll" id="gulungan" required readonly>
                </div>
        
                <div class="form-group">
                  <label for="kor">Kor: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="qore" id="kor" required readonly>
                </div>
        
                <div class="form-group">
                  <label for="lins">Line: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="lin" id="lins" required readonly>
                </div>
        
                <div class="form-group">
                  <label for="qty_baku">QTY Bahan baku:</label>
                  <input type="text" class="form-control" name="qty_bahan_baku" id="qty_baku" required readonly>
                </div>
        
                <div class="form-group">
                  <label for="qty_produksi">QTY Produksi:</label>
                  <div style="display:flex; position:relative;">
                    <input style="width:100px; text-align: center;" type="text" class="form-control" name="qty_produksi" id="qty_produksi" required readonly>  
                    <select style="width:100px;" name="satuanunit" id="satuanunit" class="form-control" required>
                    <option value="" selected disabled>Pilih Satuan</option>
                    <option value="ROLL">ROLL</option>
                    <option value="PCS">PCS</option>
                    <option value="UNIT">UNIT</option>
                    <option value="PAK">PAK</option>
                    <option value="SHEET">SHEET</option>
                    <option value="SHEET">METER</option>
                    </select> 
                    <input type="text" class="form-control" name="qty_produksi2" id="qty_produksi2" required readonly> 
                  </div>
                </div>
        
                <div class="form-group">
                  <label for="isi">Isi 1 <label name="unit" id="unit"></label>  <span class="required">*</span></label>
                  <input type="text" class="form-control" name="isi" id="isi" required readonly>
                </div>

                <hr>
                <p style="font-weight: bold">TANDA TANGAN</p>

                <div class="form-group">
                  <label for="ttd">Nama: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="ttd" id="ttd" value="<?php echo $_SESSION['name'];?>" readonly>
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="print">Cetak</button>
                  <input type="button" class="lightbox_close" value="Batal">
                </div>
              </form>
            </div>
          </div>

          <div id="PrintModal" class="modal">
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              <br><hr>
              <div class="printnow">
                  <table style="width:100%;" border="0">
                    <caption style="font-size: 14pt" class="text-center"><b>SURAT PERINTAH KERJA CV WISNU CAHAYA LABEL</b></caption>
                    
                    <tr height="2s0px">
                      <td>&nbsp;</td>
                      <td></td> 
                    </tr>                    
                    <tr>
                      <td>Nama</td>
                      <td>: <span class="cus"></span></td>
                      <td></td>

                      <!-- <td colspan="2"></td> -->
                      <td>Tanggal : <span class="spkdate"></span></td>
                    </tr>

                    <!-- <tr>
                      <td>Telp</td>
                      <td>: <span class="ptelp"></span></td> 
                      <td></td>   -->
                    <tr>
                      <!-- <td colspan="3"></td> -->
                      <td>No PO</td>
                      <td>: <span class="pcus"></span></td>
                    </tr>
                    <tr height="30px">
                      <td>&nbsp;</td>
                      <td></td>
                   
                    </tr>                    
                    <tr>      
                      <td></td>         


                      <td class="text-center" ><b><i>Mohon diproduksi dengan spesifikasi sbb:</i></b></td>
                    </tr>
                    <tr>
                      <td>No SO</td>
                      <td colspan="2">: <span class="spk"><span></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>Keterangan</td>
                      <td colspan="2">: <span class="ann"><span></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>Ukuran Label</td>
                      <td colspan="2">: <span class="slabel"><span></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>Uk. Bahan baku</td>
                      <td colspan="2">: <span class="sbaku"><span></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>Bahan</td>
                      <td colspan="2">: <span class="bah"><span></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>Gulungan</td>
                      <td colspan="2">: <span class="gul"><span></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>Kor</td>
                      <td colspan="2">: <span class="kore"><span></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>Line</td>
                      <td colspan="2">: <span class="lie"><span></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>Porporasi</td>
                      <td colspan="2">: <span class="por"><span></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>QTY Bahan baku</td>
                      <td colspan="2">: <span class="qbaku"><span></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>QTY Produksi</td>
                      <td colspan="2">: <span class="qproduk"><span></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td class='isi'>Isi 1 <span class="isiuni"><span></td>
                      <td colspan="2">: <span class="is"><span></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>Dibuat oleh</td>
                      <td colspan="2">: <span class="td"><span></td>
                      <td></td>
                    </tr>
                  </table>

                  <table style="width:97%" border="1">
                    <tr class="text-center">
                        <td colspan="5" class="text-center">Ket. Proses Produksi</td>
                      </tr>
                    <tr>
                    <tr>
                      <td class="text-center">Pembuat</td>
                      <td class="text-center">Penerima</td>
                      <td class="text-center">Bag. Produksi</td>
                      <td class="text-center">Slitting</td>
                      <td class="text-center">Total Produksi</td>
                    </tr>

                    <tr height="75px">
                      <td>&nbsp;</td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                  </table>
              </div>
            </div>
          </div>

          <!-- Modal Ongkir -->
          <div id="PeriodeResult" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close periode_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT PERIODE</h2>
              <form class="form add" id="form_periode" data-id="" novalidate>

                <div class="form-group">
                  <label>Dari Tanggal: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="startdate" id="startdate" required>
                </div>

                <div class="form-group">
                  <label>Sampai: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="enddate" id="enddate" required>
                </div>
        
                <div class="button_container" style="text-align: center">
                  <button type="submit" class="lihat">View</button>
                  <input type="button" class="periode_close" value="Cancel">
                  <input type="hidden" id="report" name="report" value="periode">
                </div>
              </form>
            </div>
          </div>

          <noscript id="noscript_container">
            <div id="noscript" class="error">
              <p>JavaScript support is needed to use this page.</p>
            </div>
          </noscript>

          <div id="message_container">
            <div id="message" class="success">
              <p>This is a success message.</p>
            </div>
          </div>

          <div id="loading_container">
            <div id="loading_container2">
              <div id="loading_container3">
                <div id="loading_container4">
                  Loading, please wait...
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "invoice_waiting"){ ?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo ucfirst(htmlspecialchars($_GET["page"])); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo ucfirst(htmlspecialchars($_GET["page"])); ?></li>
      </ol>
    </section>

    <hr>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class ="container-fluid">
          <form id="create_invoice">
          <table id="tablenya" class="datatable" style="width:100%">
            <thead>
              <tr>
                  <th></th>
                  <th>Tgl Surat Jalan</th>
                  <th>Customer</th>
                  <th>No PO</th>
                  <th>No SO</th>
                  <th>Surat Jalan</th>
                  <th>Qty</th>
                  <th>Unit</th>
                  <th>Harga</th>
                  <th>Tagihan</th>
                  <th>Ppn</th>
                  <th>Total</th>
                  <th>Biaya Kirim</th>
                  <th>Option</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th class="text-right" style="font-weight: bold">Total Amount:</th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
              </tr>
            </tfoot>
          </table>
          </form>

          <!-- Modal Invoice -->
          <div id="InvoiceModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT INVOICE</h2>
              <form class="form add" id="form_inputINV" data-id="" novalidate>

                <div class="form-group date">
                  <label for="date">Tanggal: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="date" id="date" required>
                </div>
        
                <div class="button_container" style="text-align: center">
                  <button type="submit" class="saving">Simpan</button>
                  <input type="button" class="lightbox_close" value="Batal">
                </div>
              </form>
            </div>
          </div>

          <noscript id="noscript_container">
            <div id="noscript" class="error">
              <p>JavaScript support is needed to use this page.</p>
            </div>
          </noscript>

          <div id="message_container">
            <div id="message" class="success">
              <p>This is a success message.</p>
            </div>
          </div>

          <div id="loading_container">
            <div id="loading_container2">
              <div id="loading_container3">
                <div id="loading_container4">
                  Loading, please wait...
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "invoice_procces"){ ?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo ucfirst(htmlspecialchars($_GET["page"])); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo ucfirst(htmlspecialchars($_GET["page"])); ?></li>
      </ol>
    </section>

    <hr>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class ="container-fluid">
          <table id="tablenya" class="datatable" style="width:100%">
            <thead>
              <tr>
                  <th>Diterbitkan</th>
                  <th>Jatuh Tempo</th>
                  <th>Customer</th>
                  <th>No PO</th>
                  <th>No SO</th>
                  <th>Surat Jalan</th>
                  <th>No Faktur</th>
                  <th>Qty</th>
                  <th>Unit</th>
                  <th>Harga</th>
                  <th>Tagihan</th>
                  <th>Ppn</th>
                  <th>Total</th>
                  <th>Biaya Kirim</th>
                  <th>Ekspedisi</th>
                  <th>Uom</th>
                  <th>Jumlah</th>
                  <th>Dicetak</th>
                  <th>Diinput</th>
                  <th>Option</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th class="text-right" style="font-weight: bold">Total Amount:</th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
              </tr>
            </tfoot>
          </table>

           <!-- The Modal -->
          <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">PRATINJAU PRINT</h2>
              <form class="form add" id="form_print" data-id="" novalidate>

                <hr class="baris">
                <p style="font-weight: bold" class="judul">DITERBITKAN</p>
                
                <div class="form-group tanggal">
                  <label for="tgl">Tanggal: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="tgl" id="tgl" required readonly>
                </div>

                <div class="form-group company">
                  <label for="company">Company: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="company" id="company" required readonly>
                </div>

                <div class="form-group address">
                  <label for="address">Address: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="address" id="address" required readonly>
                </div>

                <div class="form-group phone">
                  <label for="phone">Phone: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="phone" id="phone" required readonly>
                </div>

                <hr class="baris">
                <p style="font-weight: bold" class="judul">RINCIAN TAGIHAN</p>
                <div class="form-group no_faktur">
                  <label for="no_faktur">No Faktur: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="no_faktur" id="no_faktur" required readonly>
                </div>

                <div class="form-group customers">
                  <label for="customer">Customer: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="customer" id="customer" required readonly>
                </div>

                <div class="form-group billto">
                  <label for="billto">Bill Address: <span class="required">*</span></label>
                  <textarea class="form-control" name="billto" id="billto" required readonly></textarea>
                </div>

                <div class="form-group tagihan">
                  <label for="tagihan">Tagihan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="tagihan" id="tagihan" required readonly>
                  <input type="hidden" name="bill" id="bill">
                </div>

                <div class="form-group status_ppn" style="display: none">
                  <label for="status_ppn">PPN: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="status_ppn" id="status_ppn" required readonly>
                </div>

                <div class="form-group s_cost">
                  <label for="s_cost">Biaya kirim: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="s_cost" id="s_cost" required readonly>
                  <input type="hidden" name="biaya_kirim" id="biaya_kirim">
                </div>

                <div class="form-group customers">
                  <label for="no_po">No PO: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="no_po" id="no_po" required readonly>
                </div>

                <div class="form-group ship_name">
                  <label for="ship_name">Ship Name: <span class="required">*</span></label>
                  <textarea class="form-control" name="ship_name" id="ship_name" required readonly></textarea>
                </div>

                <div class="form-group shipto">
                  <label for="shipto">Ship Address: <span class="required">*</span></label>
                  <textarea class="form-control" name="shipto" id="shipto" required readonly></textarea>
                </div>

                <div class="form-group telp">
                  <label for="telp">Telp: <span class="required">*</span></label>
                  <input type="number" min="0" class="form-control" name="telp" id="telp">
                </div>
                
                <div class="datanyanih"></div>

                <hr class="baris">
                <p style="font-weight: bold" class="judul">RINCIAN PEMBAYARAN</p>

                <div class="form-group pilihBANK">
                  <label>Bank: <span class="required">*</span></label>
                  <select class="form-control" id="pilihBANK" name="pilihBANK" required></select>
                </div>
                
                <hr class="baris">
                <p style="font-weight: bold" class="judul">TANDA TANGAN</p>

                <div class="form-group ttds">
                  <label for="ttd">Nama: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="ttd" id="ttd" value="<?php echo $_SESSION['name'];?>" required readonly>
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="print">Cetak</button>
                  <input type="button" class="lightbox_close" value="Batal">
                  <input type="hidden" id="id_fk" value="">
                </div>
              </form>
            </div>
          </div>

          <div id="PrintModal" class="modal">
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              <br><hr>
              <div class="printnow">
                <div class="row margin-bottom-lg">
                  <div class="col-xs-12">
                    <div class="row delivery-orders-header"></div>
                    <br>
                    <div class="row delivery-orders-title" style="font-size: 12px">
                      <div class="col-md-9 col-xs-9">
                        <p><strong>Bill to : </strong><span class="bill_nama"></span></p>
                        <p class="bill_alamat"></p>
                        <p><strong>Ship to : </strong><span class="ship_nama"></span></p>
                        <p class="ship_alamat"></p>
                      </div>
                      <div class="col-md-3 col-xs-3">
                        <p>Dated : <span class="invoice_date"></span></p>
                        <p>PO No : <span class="po_customer"></span></p>
                        <p>Payment Term: <span class="payment_term">30 DAYS</span></p>
                        <p>Payment Due : <span class="payment_due"></span></p>
                      </div>
                    </div>
                  </div>
                </div>
              
                <div class="row" style="font-size: 12px;">
                  <div class="col-md-12">
                  <table class="table table-bordered">
                    <thead class="thead">
                      <tr>
                        <td class="text-center">No</td>
                        <td class="text-center" style="width:250px">Item</td>
                        <td class="text-center">No SO</td>
                        <td class="text-center">Qty</td>
                        <td class="text-center">Unit</td>
                        <td class="text-center">Unit Price</td>
                        <td class="text-center">Amount</td>
                      </tr>
                    </thead>
                    <tbody class="tbody"></tbody>
                  </table>
                  </div>
                </div>

                <div class="row" style="font-size: 12px;">
                  <div class="col-md-8 col-xs-8">
                    <p><strong>Delivery Order No :</strong></p>
                    <p class="suratjalan"></p>
                    <p><strong>Bank Account No :</strong></p>
                    <p>A/C : <span class="rekening"></span></p>
                    <p>A/N : <span class="atasnama"></span></p>
                    <p>Bank : <span class="namabank"></span></p>
                  </div>
                  <div class="col-md-2 col-xs-2">
                    <p><strong>Sub Total : </strong></p>
                    <p><strong>VAT 11% : </strong></p>
                    <p><strong>Total : </strong></p>
                    <hr class="line_cost" style="border: 1px solid #666; display: none">
                    <p class="label_cost" style="display: none;margin-bottom: 0px"><strong>Shipping Costs : </strong></p>
                  </div>
                  <div class="col-md-2 col-xs-2 text-right">
                    <p class="subtotal"></p>
                    <p class="vat"></p>
                    <p class="jumlah"></p>
                    <hr class="line_cost" style="border: 1px solid #666; display: none">
                    <p class="cost" style="display: none;"></p>
                  </div>
                </div>

                <div class="row" style="font-size: 12px;">
                  <div class="col-xs-12 col-md-12">
                    <div class="text-right">
                      <p>Issued by,</p>
                      <p class="ttd_person"></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Modal Invoice -->
          <div id="InvoiceModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT INVOICE</h2>
              <form class="form lunas" id="form_inputINV" data-id="" novalidate>

                <div class="form-group date">
                  <label for="date">Tanggal Lunas: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="date" id="date" required>
                </div>

                <div class="form-group ket">
                  <label for="keterangan">Keterangan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="ket" id="ket" placeholder="LUNAS" required>
                </div>
        
                <div class="button_container" style="text-align: center">
                  <button type="submit" class="saving">Simpan</button>
                  <input type="button" class="lightbox_close" value="Batal">
                </div>
              </form>
            </div>
          </div>

          <!-- Modal Ongkir -->
          <div id="PeriodeResult" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close periode_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT PERIODE</h2>
              <form class="form add" id="form_periode" data-id="" novalidate>

                <div class="form-group">
                  <label>Dari Tanggal: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="startdate" id="startdate" required>
                </div>

                <div class="form-group">
                  <label>Sampai: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="enddate" id="enddate" required>
                </div>
        
                <div class="button_container" style="text-align: center">
                  <button type="submit" class="lihat">View</button>
                  <input type="button" class="periode_close" value="Cancel">
                  <input type="hidden" id="report" value="periode">
                </div>
              </form>
            </div>
          </div>

          <noscript id="noscript_container">
            <div id="noscript" class="error">
              <p>JavaScript support is needed to use this page.</p>
            </div>
          </noscript>

          <div id="message_container">
            <div id="message" class="success">
              <p>This is a success message.</p>
            </div>
          </div>

          <div id="loading_container">
            <div id="loading_container2">
              <div id="loading_container3">
                <div id="loading_container4">
                  Loading, please wait...
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "invoice_duedate"){ ?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo ucfirst(htmlspecialchars($_GET["page"])); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo ucfirst(htmlspecialchars($_GET["page"])); ?></li>
      </ol>
    </section>
    <hr>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class ="container-fluid">
          <table id="tablenya" class="datatable nowrap" style="width:100%">
            <thead>
              <tr>
                  <th>Diterbitkan</th>
                  <th>Jatuh Tempo</th>
                  <th>Customer</th>
                  <th>No PO</th>
                  <th>No SO</th>
                  <th>Surat Jalan</th>
                  <th>No Faktur</th>
                  <th>Qty</th>
                  <th>Unit</th>
                  <th>Harga</th>
                  <th>Tagihan</th>
                  <th>Ppn</th>
                  <th>Total</th>
                  <th>Biaya Kirim</th>
                  <th>Ekspedisi</th>
                  <th>Uom</th>
                  <th>Jumlah</th>
                  <th>Dicetak</th>
                  <th>Diinput</th>
                  <th>Option</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th class="text-right" style="font-weight: bold">Total Amount :</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
              </tr>
            </tfoot> 
          </table>

          <!-- The Modal -->
          <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">PRATINJAU PRINT</h2>
              <form class="form add" id="form_print" data-id="" novalidate>

                <hr class="baris">
                <p style="font-weight: bold" class="judul">DITERBITKAN</p>
                
                <div class="form-group tanggal">
                  <label for="tgl">Tanggal: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="tgl" id="tgl" required readonly>
                </div>

                <div class="form-group company">
                  <label for="company">Company: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="company" id="company" required readonly>
                </div>

                <div class="form-group address">
                  <label for="address">Address: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="address" id="address" required readonly>
                </div>

                <div class="form-group phone">
                  <label for="phone">Phone: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="phone" id="phone" required readonly>
                </div>

                <hr class="baris">
                <p style="font-weight: bold" class="judul">RINCIAN TAGIHAN</p>
                <div class="form-group no_faktur">
                  <label for="no_faktur">No Faktur: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="no_faktur" id="no_faktur" required readonly>
                </div>

                <div class="form-group customers">
                  <label for="customer">Customer: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="customer" id="customer" required readonly>
                </div>

                <div class="form-group billto">
                  <label for="billto">Bill Address: <span class="required">*</span></label>
                  <textarea class="form-control" name="billto" id="billto" required readonly></textarea>
                </div>

                <div class="form-group tagihan">
                  <label for="tagihan">Tagihan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="tagihan" id="tagihan" required readonly>
                  <input type="hidden" name="bill" id="bill">
                </div>

                <div class="form-group status_ppn" style="display: none">
                  <label for="status_ppn">PPN: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="status_ppn" id="status_ppn" required readonly>
                </div>

                <div class="form-group s_cost">
                  <label for="s_cost">Biaya kirim: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="s_cost" id="s_cost" required readonly>
                  <input type="hidden" name="biaya_kirim" id="biaya_kirim">
                </div>

                <div class="form-group customers">
                  <label for="no_po">No PO: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="no_po" id="no_po" required readonly>
                </div>

                <div class="form-group ship_name">
                  <label for="ship_name">Ship Name: <span class="required">*</span></label>
                  <textarea class="form-control" name="ship_name" id="ship_name" required readonly></textarea>
                </div>

                <div class="form-group shipto">
                  <label for="shipto">Ship Address: <span class="required">*</span></label>
                  <textarea class="form-control" name="shipto" id="shipto" required readonly></textarea>
                </div>

                <div class="form-group telp">
                  <label for="telp">Telp: <span class="required">*</span></label>
                  <input type="number" min="0" class="form-control" name="telp" id="telp">
                </div>
                
                <div class="datanyanih"></div>

                <hr class="baris">
                <p style="font-weight: bold" class="judul">RINCIAN PEMBAYARAN</p>

                <div class="form-group pilihBANK">
                  <label>Bank: <span class="required">*</span></label>
                  <select class="form-control" id="pilihBANK" name="pilihBANK" required></select>
                </div>
                
                <hr class="baris">
                <p style="font-weight: bold" class="judul">TANDA TANGAN</p>

                <div class="form-group ttds">
                  <label for="ttd">Nama: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="ttd" id="ttd" value="<?php echo $_SESSION['name'];?>" required readonly>
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="print">Cetak</button>
                  <input type="button" class="lightbox_close" value="Batal">
                  <input type="hidden" id="id_fk" value="">
                </div>
              </form>
            </div>
          </div>

          <div id="PrintModal" class="modal">
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              <br><hr>
              <div class="printnow">
                <div class="row margin-bottom-lg">
                  <div class="col-xs-12">
                    <div class="row delivery-orders-header"></div>
                    <br>
                    <div class="row delivery-orders-title" style="font-size: 12px">
                      <div class="col-md-9 col-xs-9">
                        <p><strong>Bill to : </strong><span class="bill_nama"></span></p>
                        <p class="bill_alamat"></p>
                        <p><strong>Ship to : </strong><span class="ship_nama"></span></p>
                        <p class="ship_alamat"></p>
                      </div>
                      <div class="col-md-3 col-xs-3">
                        <p>Dated : <span class="invoice_date"></span></p>
                        <p>PO No : <span class="po_customer"></span></p>
                        <p>Payment Term: <span class="payment_term">30 DAYS</span></p>
                        <p>Payment Due : <span class="payment_due"></span></p>
                      </div>
                    </div>
                  </div>
                </div>
              
                <div class="row" style="font-size: 12px;">
                  <div class="col-md-12">
                  <table class="table table-bordered">
                    <thead class="thead">
                      <tr>
                        <td class="text-center">No</td>
                        <td class="text-center" style="width:250px">Item</td>
                        <td class="text-center">No SO</td>
                        <td class="text-center">Qty</td>
                        <td class="text-center">Unit</td>
                        <td class="text-center">Unit Price</td>
                        <td class="text-center">Amount</td>
                      </tr>
                    </thead>
                    <tbody class="tbody"></tbody>
                  </table>
                  </div>
                </div>

                <div class="row" style="font-size: 12px;">
                  <div class="col-md-8 col-xs-8">
                    <p><strong>Delivery Order No :</strong></p>
                    <p class="suratjalan"></p>
                    <p><strong>Bank Account No :</strong></p>
                    <p>A/C : <span class="rekening"></span></p>
                    <p>A/N : <span class="atasnama"></span></p>
                    <p>Bank : <span class="namabank"></span></p>
                  </div>
                  <div class="col-md-2 col-xs-2">
                    <p><strong>Sub Total : </strong></p>
                    <p><strong>VAT 11% : </strong></p>
                    <p><strong>Total : </strong></p>
                    <hr class="line_cost" style="border: 1px solid #666; display: none">
                    <p class="label_cost" style="display: none;margin-bottom: 0px"><strong>Shipping Costs : </strong></p>
                  </div>
                  <div class="col-md-2 col-xs-2 text-right">
                    <p class="subtotal"></p>
                    <p class="vat"></p>
                    <p class="jumlah"></p>
                    <hr class="line_cost" style="border: 1px solid #666; display: none">
                    <p class="cost" style="display: none;"></p>
                  </div>
                </div>

                <div class="row" style="font-size: 12px;">
                  <div class="col-xs-12 col-md-12">
                    <div class="text-right">
                      <p>Issued by,</p>
                      <p class="ttd_person"></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Modal Invoice -->
          <div id="InvoiceModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT INVOICE</h2>
              <form class="form lunas" id="form_inputINV" data-id="" novalidate>

                <div class="form-group date">
                  <label for="date">Tanggal Lunas: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="date" id="date" required>
                </div>

                <div class="form-group ket">
                  <label for="keterangan">Keterangan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="ket" id="ket" placeholder="LUNAS" required>
                </div>
        
                <div class="button_container" style="text-align: center">
                  <button type="submit" class="saving">Simpan</button>
                  <input type="button" class="lightbox_close" value="Batal">
                </div>
              </form>
            </div>
          </div>

          <noscript id="noscript_container">
            <div id="noscript" class="error">
              <p>JavaScript support is needed to use this page.</p>
            </div>
          </noscript>

          <div id="message_container">
            <div id="message" class="success">
              <p>This is a success message.</p>
            </div>
          </div>

          <div id="loading_container">
            <div id="loading_container2">
              <div id="loading_container3">
                <div id="loading_container4">
                  Loading, please wait...
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "invoice_done"){ ?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo ucfirst(htmlspecialchars($_GET["page"])); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo ucfirst(htmlspecialchars($_GET["page"])); ?></li>
      </ol>
    </section>

    <hr>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class ="container-fluid">
          <table id="tablenya" class="datatable" style="width:100%">
            <caption>
              <div id="widthSortby" class="input-group">
                <span class="input-group-addon">Sort by </span>
                <select id="sortby" class="form-control"></select>
                <span class="input-group-btn">
                  <button type="button" id="LoadData" class="btn btn-default">View</button>
                </span>
              </div>
            </caption>
            <thead>
              <tr>
                  <th>Diterbitkan</th>
                  <th>Jatuh Tempo</th>
                  <th>Customer</th>
                  <th>No PO</th>
                  <th>No SO</th>
                  <th>Surat Jalan</th>
                  <th>No Faktur</th>
                  <th>Qty</th>
                  <th>Unit</th>
                  <th>Harga</th>
                  <th>Tagihan</th>
                  <th>Ppn</th>
                  <th>Total</th>
                  <th>Biaya Kirim</th>
                  <th>Ekspedisi</th>
                  <th>Uom</th>
                  <th>Jumlah</th>
                  <th>Keterangan</th>
                  <th>Dicetak</th>
                  <th>Diinput</th>
                  <th>Option</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th class="text-right" style="font-weight: bold">Total Amount:</th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
              </tr>
            </tfoot>
          </table>

          <!-- The Modal -->
          <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">PRATINJAU PRINT</h2>
              <form class="form add" id="form_print" data-id="" novalidate>

                <hr class="baris">
                <p style="font-weight: bold" class="judul">DITERBITKAN</p>
                
                <div class="form-group tanggal">
                  <label for="tgl">Tanggal: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="tgl" id="tgl" required readonly>
                </div>

                <div class="form-group company">
                  <label for="company">Company: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="company" id="company" required readonly>
                </div>

                <div class="form-group address">
                  <label for="address">Address: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="address" id="address" required readonly>
                </div>

                <div class="form-group phone">
                  <label for="phone">Phone: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="phone" id="phone" required readonly>
                </div>

                <hr class="baris">
                <p style="font-weight: bold" class="judul">RINCIAN TAGIHAN</p>
                <div class="form-group no_faktur">
                  <label for="no_faktur">No Faktur: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="no_faktur" id="no_faktur" required readonly>
                </div>

                <div class="form-group customers">
                  <label for="customer">Customer: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="customer" id="customer" required readonly>
                </div>

                <div class="form-group billto">
                  <label for="billto">Bill Address: <span class="required">*</span></label>
                  <textarea class="form-control" name="billto" id="billto" required readonly></textarea>
                </div>

                <div class="form-group tagihan">
                  <label for="tagihan">Tagihan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="tagihan" id="tagihan" required readonly>
                  <input type="hidden" name="bill" id="bill">
                </div>

                <div class="form-group status_ppn" style="display: none">
                  <label for="status_ppn">PPN: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="status_ppn" id="status_ppn" required readonly>
                </div>

                <div class="form-group s_cost">
                  <label for="s_cost">Biaya kirim: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="s_cost" id="s_cost" required readonly>
                  <input type="hidden" name="biaya_kirim" id="biaya_kirim">
                </div>

                <div class="form-group customers">
                  <label for="no_po">No PO: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="no_po" id="no_po" required readonly>
                </div>

                <div class="form-group ship_name">
                  <label for="ship_name">Ship Name: <span class="required">*</span></label>
                  <textarea class="form-control" name="ship_name" id="ship_name" required readonly></textarea>
                </div>

                <div class="form-group shipto">
                  <label for="shipto">Ship Address: <span class="required">*</span></label>
                  <textarea class="form-control" name="shipto" id="shipto" required readonly></textarea>
                </div>

                <div class="form-group telp">
                  <label for="telp">Telp: <span class="required">*</span></label>
                  <input type="number" min="0" class="form-control" name="telp" id="telp">
                </div>
                
                <div class="datanyanih"></div>

                <hr class="baris">
                <p style="font-weight: bold" class="judul">RINCIAN PEMBAYARAN</p>

                <div class="form-group pilihBANK">
                  <label>Bank: <span class="required">*</span></label>
                  <select class="form-control" id="pilihBANK" name="pilihBANK" required></select>
                </div>
                
                <hr class="baris">
                <p style="font-weight: bold" class="judul">TANDA TANGAN</p>

                <div class="form-group ttds">
                  <label for="ttd">Nama: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="ttd" id="ttd" value="<?php echo $_SESSION['name'];?>" required readonly>
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="print">Cetak</button>
                  <input type="button" class="lightbox_close" value="Batal">
                  <input type="hidden" id="id_fk" value="">
                </div>
              </form>
            </div>
          </div>

          <!-- Modal Ongkir -->
          <div id="PeriodeResult" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close periode_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT PERIODE</h2>
              <form class="form add" id="form_periode" data-id="" novalidate>

                <div class="form-group">
                  <label>Dari Tanggal: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="startdate" id="startdate" required>
                </div>

                <div class="form-group">
                  <label>Sampai: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="enddate" id="enddate" required>
                </div>
        
                <div class="button_container" style="text-align: center">
                  <button type="submit" class="lihat">View</button>
                  <input type="button" class="periode_close" value="Cancel">
                  <input type="hidden" id="report" value="periode">
                </div>
              </form>
            </div>
          </div>

          <div id="PrintModal" class="modal">
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              <br><hr>
              <div class="printnow">
                <div class="row margin-bottom-lg">
                  <div class="col-xs-12">
                    <div class="row delivery-orders-header"></div>
                    <br>
                    <div class="row delivery-orders-title" style="font-size: 12px">
                      <div class="col-md-9 col-xs-9">
                        <p><strong>Bill to : </strong><span class="bill_nama"></span></p>
                        <p class="bill_alamat"></p>
                        <p><strong>Ship to : </strong><span class="ship_nama"></span></p>
                        <p class="ship_alamat"></p>
                      </div>
                      <div class="col-md-3 col-xs-3">
                        <p>Dated : <span class="invoice_date"></span></p>
                        <p>PO No : <span class="po_customer"></span></p>
                        <p>Payment Term: <span class="payment_term">30 DAYS</span></p>
                        <p>Payment Due : <span class="payment_due"></span></p>
                      </div>
                    </div>
                  </div>
                </div>
              
                <div class="row" style="font-size: 12px;">
                  <div class="col-md-12">
                  <table class="table table-bordered">
                    <thead class="thead">
                      <tr>
                        <td class="text-center">No</td>
                        <td class="text-center" style="width:250px">Item</td>
                        <td class="text-center">No SO</td>
                        <td class="text-center">Qty</td>
                        <td class="text-center">Unit</td>
                        <td class="text-center">Unit Price</td>
                        <td class="text-center">Amount</td>
                      </tr>
                    </thead>
                    <tbody class="tbody"></tbody>
                  </table>
                  </div>
                </div>

                <div class="row" style="font-size: 12px;">
                  <div class="col-md-8 col-xs-8">
                    <p><strong>Delivery Order No :</strong></p>
                    <p class="suratjalan"></p>
                    <p><strong>Bank Account No :</strong></p>
                    <p>A/C : <span class="rekening"></span></p>
                    <p>A/N : <span class="atasnama"></span></p>
                    <p>Bank : <span class="namabank"></span></p>
                  </div>
                  <div class="col-md-2 col-xs-2">
                    <p><strong>Sub Total : </strong></p>
                    <p><strong>VAT 11% : </strong></p>
                    <p><strong>Total : </strong></p>
                    <hr class="line_cost" style="border: 1px solid #666; display: none">
                    <p class="label_cost" style="display: none;margin-bottom: 0px"><strong>Shipping Costs : </strong></p>
                  </div>
                  <div class="col-md-2 col-xs-2 text-right">
                    <p class="subtotal"></p>
                    <p class="vat"></p>
                    <p class="jumlah"></p>
                    <hr class="line_cost" style="border: 1px solid #666; display: none">
                    <p class="cost" style="display: none;"></p>
                  </div>
                </div>

                <div class="row" style="font-size: 12px;">
                  <div class="col-xs-12 col-md-12">
                    <div class="text-right">
                      <p>Issued by,</p>
                      <p class="ttd_person"></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <noscript id="noscript_container">
            <div id="noscript" class="error">
              <p>JavaScript support is needed to use this page.</p>
            </div>
          </noscript>

          <div id="message_container">
            <div id="message" class="success">
              <p>This is a success message.</p>
            </div>
          </div>

          <div id="loading_container">
            <div id="loading_container2">
              <div id="loading_container3">
                <div id="loading_container4">
                  Loading, please wait...
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "aging"){ ?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo ucfirst(htmlspecialchars($_GET["page"])); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo ucfirst(htmlspecialchars($_GET["page"])); ?></li>
      </ol>
    </section>

    <hr>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class ="container-fluid">
          <table id="tablenya" class="datatable" style="width:100%">
            <caption>
              <div id="widthSortby" class="input-group">
                <span class="input-group-addon">Sort by </span>
                <select id="sortby" class="form-control"></select>
                <span class="input-group-btn">
                  <button type="button" id="LoadData" class="btn btn-default">View</button>
                </span>
              </div>
            </caption>
            <thead>
              <tr>
                  <th>No</th>
                  <th>Customer</th>
                  <th>Company</th>
                  <th>Invoice No</th>
                  <th>No Surat</th>
                  <th>No SO</th>
                  <th>No PO</th>
                  <th>Invoice Date</th>
                  <th>Due Date</th>
                  <th>Amount</th>
                  <th>Tgl Lunas</th>
                  <th>Keterangan</th>
                  <th>Ongkir</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

          <table id="tablePrint" class="datatable nowrap" style="display: none">
            <thead>
              <tr>
                  <th>No</th>
                  <th>Customer</th>
                  <th>Company</th>
                  <th>Invoice No</th>
                  <th>No Surat</th>
                  <th>No SO</th>
                  <th>No PO</th>
                  <th>Invoice Date</th>
                  <th>Due Date</th>
                  <th>Amount</th>
                  <th>Tgl Lunas</th>
                  <th>Keterangan</th>
                  <th>Ongkir</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th class="text-right" style="font-weight: bold">Total :</th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
            </tfoot>          
          </table>

          <!-- Modal Ongkir -->
          <div id="PeriodeResult" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close periode_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT PERIODE</h2>
              <form class="form add" id="form_periode" data-id="" novalidate>

                <div class="form-group">
                  <label>Dari Tanggal: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="dari" id="dari" required>
                </div>

                <div class="form-group">
                  <label>Sampai: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="sampai" id="sampai" required>
                </div>
        
                <div class="button_container" style="text-align: center">
                  <button type="submit" class="lihat">View</button>
                  <input type="button" class="periode_close" value="Cancel">
                </div>
              </form>
            </div>
          </div>

          <noscript id="noscript_container">
            <div id="noscript" class="error">
              <p>JavaScript support is needed to use this page.</p>
            </div>
          </noscript>

          <div id="message_container">
            <div id="message" class="success">
              <p>This is a success message.</p>
            </div>
          </div>

          <div id="loading_container">
            <div id="loading_container2">
              <div id="loading_container3">
                <div id="loading_container4">
                  Loading, please wait...
                </div>
              </div>
            </div>
          </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "user"){ ?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo ucfirst(htmlspecialchars($_GET["page"])); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo ucfirst(htmlspecialchars($_GET["page"])); ?></li>
      </ol>
    </section>

    <hr>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">


        <div class ="container-fluid">
          <button type="button" class="button" id="add_inputUSER">Input USER</button>
          <table id="tablenya" class="datatable responsive" style="width:100%">
            <thead>
              <tr>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Peran</th>
                  <th>Status Email</th>
                  <th>Status Akun</th>
                  <th>Option</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

          <!-- The Modal -->
          <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT USER</h2>
              <form class="form add" id="form_inputUSER" data-id="" novalidate>

                <div class="form-group name">
                  <label for="name">Nama: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="name" id="name" required>
                </div>

                <div class="form-group email">
                  <label for="email">Email: <span class="required">*</span></label>
                  <input type="email" class="form-control" name="email" id="email" required>
                </div>

                <div class="form-group password">
                  <label for="password">Password: <span class="required">*</span></label>
                  <input type="password" class="form-control" name="password" id="password" required>
                </div>

                <div class="form-group role">
                  <label>Peran sbg: <span class="required">*</span></label>
                  <select class="form-control" id="role" name="role" required>
                    <option value="" selected disabled>Pilih peran</option>
                    <option value="1">Root</option>
                    <option value="2">Administrator</option>
                    <option value="3">Sales Order</option>
                    <option value="4">Finance</option>
                    <option value="5">Guest</option>
                    <option value="6">Production</option>
                    </select>
                </div>

                <div class="form-group status">
                  <label>Status email: <span class="required">*</span></label>
                  <select class="form-control" id="status" name="status" required>
                    <option value="" disabled>Pilih status</option>
                    <option value="0">Not Verified</option>
                    <option value="1" selected>Verified</option>
                    </select>
                </div>

                <div class="form-group status">
                  <label>Status akun: <span class="required">*</span></label>
                  <select class="form-control" id="account" name="account" required>
                    <option value="" disabled>Pilih account</option>
                    <option value="0">Inactive</option>
                    <option value="1" selected>Active</option>
                    </select>
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="saving">Submit</button>
                  <input type="button" class="lightbox_close" value="Cancel">
                </div>
              </form>
            </div>

          </div>

          <noscript id="noscript_container">
            <div id="noscript" class="error">
              <p>JavaScript support is needed to use this page.</p>
            </div>
          </noscript>

          <div id="message_container">
            <div id="message" class="success">
              <p>This is a success message.</p>
            </div>
          </div>

          <div id="loading_container">
            <div id="loading_container2">
              <div id="loading_container3">
                <div id="loading_container4">
                  Loading, please wait...
                </div>
              </div>
            </div>
          </div>
        </div>


      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "profile"){ ?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo ucfirst(htmlspecialchars($_GET["page"])); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo ucfirst(htmlspecialchars($_GET["page"])); ?></li>
      </ol>
    </section>

    <hr>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">

        <div class ="container-fluid">
          <form class="form-horizontal formEditProfile" role="form">
            <!-- left column -->
            <div class="col-md-3 avatars">
              <div class="text-center">
              <?php if(empty($_SESSION['picture'])){ ?>
                <img src="../files/img/default-avatar.jpg" class="img-circle" alt="User Image">
              <?php } else { ?>
                <img src="../files/uploads/<?php echo $_SESSION['picture']; ?>" class="img-circle" alt="User Image">
              <?php } ?>
              </div>
            </div>
          
            <!-- edit form column -->
            <div class="col-md-9 personal-info">
              <div class="form-group">
                <label class="col-lg-3 control-label">Name:</label>
                <div class="col-lg-8">
                  <input class="form-control" type="text" placeholder="<?php echo $_SESSION['name']?>" value="<?php echo $_SESSION['name']?>" name="name" disabled>
                </div>
              </div>

              <div class="form-group">
                <label class="col-lg-3 control-label">Email:</label>
                <div class="col-lg-8">
                  <input class="form-control" type="email" placeholder="<?php echo $_SESSION['email']?>" value="<?php echo $_SESSION['email']?>" name="email" disabled>
                </div>
              </div>

              <div class="form-group">
                <label class="col-lg-3 control-label">Role:</label>
                <div class="col-lg-8">

                  <?php
                    if($_SESSION['role'] == '1'){ ?>
                      <input class="form-control" type="text" name="role" placeholder="Root" value="Root" disabled>
                    <?php } else if($_SESSION['role'] == '2'){ ?>
                      <input class="form-control" type="text" name="role" placeholder="Administrator" value="Administrator" disabled>
                    <?php } elseif ($_SESSION['role'] == '3') { ?>
                      <input class="form-control" type="text" name="role" placeholder="Sales Order" value="Sales Order" disabled>
                    <?php } elseif ($_SESSION['role'] == '4') { ?>
                      <input class="form-control" type="text" name="role" placeholder="Purchasing" value="Purchasing" disabled>
                    <?php } elseif ($_SESSION['role'] == '5') { ?>
                      <input class="form-control" type="text" name="role" placeholder="Guest" value="Guest" disabled>
                    <?php } elseif ($_SESSION['role'] == '6') { ?>
                      <input class="form-control" type="text" name="role" placeholder="Production" value="Production" disabled>
                    <?php } else { ?>
                      <input class="form-control" type="text" name="role" placeholder="" value="" disabled>
                  <?php } ?>

                  
                </div>
              </div>

              <div class="form-group">
                <label class="col-lg-3 control-label">Email status:</label>
                <div class="col-lg-8">

                  <?php
                    if($_SESSION['status'] == '0'){ ?>
                      <input class="form-control" type="text" name="emailStatus" placeholder="Not verified" value="Not verified" disabled>
                    <?php } else if($_SESSION['status'] == '1'){ ?>
                      <input class="form-control" type="text" name="emailStatus" placeholder="Verified" value="Verified" disabled>
                    <?php } else { ?>
                      <input class="form-control" type="text" name="emailStatus" placeholder="" value="" disabled>
                  <?php } ?>

                </div>
              </div>

              <div class="form-group">
                <label class="col-lg-3 control-label">Account status:</label>
                <div class="col-lg-8">

                   <?php
                    if($_SESSION['account'] == '0'){ ?>
                      <input class="form-control" type="text" name="accountStatus" placeholder="Inactive" value="Inactive" disabled>
                      <br/>
                      <div class="alert alert-danger">
                        <strong>Account inactive. </strong><br/> Please contact the Administrator to activate your account and get access.
                      </div>
                    <?php } else if($_SESSION['account'] == '1'){ ?>
                      <input class="form-control" type="text" name="accountStatus" placeholder="Active" value="Active" disabled>
                    <?php } else { ?>
                      <input class="form-control" type="text" name="accountStatus" placeholder="" value="" disabled>
                  <?php } ?>
                  
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-3 control-label"></label>
                <div class="col-md-8">
                  <input type="button" class="btn btn-primary buttonEditProfile" value="Ubah profil">
                </div>
              </div>

            </div>
          </form>

          <!-- The Modal -->
          <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">UBAH PROFIL</h2>
              <form class="form add" id="form_ubahProfil" data-id="<?php echo $_SESSION['id']; ?>" novalidate>

                <div class="form-group nama">
                  <label for="name">Nama: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="name" id="name" value="<?php echo $_SESSION['name']?>" required>
                </div>

                <div class="form-group email">
                  <label for="email">Email: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="email" id="email" value="<?php echo $_SESSION['email']?>" required>
                </div>

                <div class="form-group password">
                  <label for="password">Password:</label>
                  <input type="password" class="form-control" name="password" id="password" required>
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="saving">Simpan</button>
                  <input type="button" class="lightbox_close" value="Batal">
                </div>
              </form>
            </div>
          </div>

          <noscript id="noscript_container">
              <div id="noscript" class="error">
                <p>JavaScript support is needed to use this page.</p>
              </div>
            </noscript>

          <div id="message_container">
            <div id="message" class="success">
              <p>This is a success message.</p>
            </div>
          </div>

          <div id="loading_container">
            <div id="loading_container2">
              <div id="loading_container3">
                <div id="loading_container4">
                  Loading, please wait...
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "label"){ ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo ucfirst(htmlspecialchars($_GET["page"])); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo ucfirst(htmlspecialchars($_GET["page"])); ?></li>
      </ol>
    </section>

    <hr>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">

        <div class ="container-fluid">
          <?php if($_SESSION['role'] != '5'){ ?>
            <button type="button" class="button" id="add_inputLABEL">Input Label</button>
            <button type="button" class="button" id="log_label">History Stock</button>
          <?php } ?>
          <table id="tablenya" class="datatable" style="width:100%">
            <thead>
              <tr>
                  <th>No</th>
                  <th>Rak</th>
                  <th>Customer</th>
                  <th>Nama Barang</th>
                  <th>Ukuran</th>
                  <th>Bahan</th>
                  <th>Core</th>
                  <th>Line</th>
                  <th>Isi Roll</th>
                  <th>Stock</th>
                  <th>Diinput</th>
                  <th>Option</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

          <table id="tablenya2" class="datatable" style="display:none;width:100%">
            <caption>
              <div id="widthSortby" class="input-group">
                <span class="input-group-addon">Sort by </span>
                <select id="sortby" class="form-control"></select>
                <span class="input-group-btn">
                  <button type="button" id="LoadData" class="btn btn-default">View</button>
                </span>
              </div>
            </caption>
            <thead>
              <tr>
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>Rak</th>
                  <th>Nama</th>
                  <th>No S.Jalan</th>
                  <th>No PO</th>
                  <th>Status</th>
                  <th>Nama Barang</th>
                  <th>Ukuran</th>
                  <th>Bahan</th>
                  <th>Core</th>
                  <th>Line</th>
                  <th>Gulungan</th>
                  <th>Content (PCS)</th>
                  <th>Satuan</th>
                  <th>Stok Awal</th>
                  <th>Stok Masuk</th>
                  <th>Stok Keluar</th>
                  <th>Stok Akhir</th>
                  <th>Diinput</th>
                  <th>Option</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>

          <!-- The Modal -->
          <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT LABEL</h2>
              <form class="form add" id="form_inputLABEL" data-id="" novalidate>

                <div class="form-group rak">
                  <label for="rak">Rak:</label>
                  <input type="text" class="form-control" name="rak" id="rak">
                </div>

                <div class="form-group customer">
                  <label for="name">Customer:</label>
                  <input type="text" class="form-control" name="customer" id="customer">
                </div>

                <div class="form-group product">
                  <label for="product">Nama Barang: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="product" id="product" required>
                </div>

                <div class="form-group size">
                  <label for="Ukuran">Ukuran: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="size" id="size" required>
                </div>

                <div class="form-group material">
                  <label for="Bahan">Bahan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="material" id="material" required>
                </div>

                <div class="form-group core">
                  <label for="qore">Qore: <span class="required">*</span></label>
                  <input type="number" min="0"class="form-control" name="core" id="core" required>
                </div>

                <div class="form-group line">
                  <label for="line">Line: <span class="required">*</span></label>
                  <input type="number" min="0" class="form-control" name="line" id="line" required>
                </div>

                <div class="form-group per_roll">
                  <label for="per_roll">Per Roll: <span class="required">*</span></label>
                  <input type="number" class="form-control" name="per_roll" id="per_roll" required>
                </div>

                <div class="form-group stock" style="display: none">
                  <label for="stok">Stok: <span class="required">*</span></label>
                  <input type="number" min="0" class="form-control" name="stock" id="stock" required>
                </div>

                <div class="form-group nama_" style="display: none">
                  <label for="nama_">Customer: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="nama_" id="nama_" required>
                </div>

                <div class="form-group tgl_" style="display: none">
                  <label for="tanggal">Tanggal: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="tgl_" id="tgl_" required>
                </div>

                <div class="form-group nosj_" style="display: none">
                  <label for="nosj">No Surat Jalan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="nosj_" id="nosj_" required>
                </div>

                <div class="form-group nopo_" style="display: none">
                  <label for="nopo">No PO: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="nopo_" id="nopo_" required>
                </div>

                <div class="form-group roll_" style="display: none">
                  <label for="roll">Gulungan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="roll_" id="roll_" required>
                </div>

                <div class="form-group content_" style="display: none">
                  <label for="content">Content (PCS): <span class="required">*</span></label>
                  <input type="text" class="form-control" name="content_" id="content_" required>
                </div>

                <div class="form-group unit_" style="display: none">
                  <label for="unit">Satuan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="unit_" id="unit_" required>
                </div>

                <div class="form-group smasuk_" style="display: none">
                  <label for="smasuk">Stok Masuk: <span class="required">*</span></label>
                  <input type="number" class="form-control" name="smasuk_" id="smasuk_" required>
                </div>

                <div class="form-group skeluar_" style="display: none">
                  <label for="skeluar">Stok Keluar: <span class="required">*</span></label>
                  <input type="number" class="form-control" name="skeluar_" id="skeluar_" required>
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="saving">Simpan</button>
                  <input type="button" class="lightbox_close" value="Batal">
                </div>
              </form>
            </div>

          </div>

          <noscript id="noscript_container">
            <div id="noscript" class="error">
              <p>JavaScript support is needed to use this page.</p>
            </div>
          </noscript>

          <div id="message_container">
            <div id="message" class="success">
              <p>This is a success message.</p>
            </div>
          </div>

          <div id="loading_container">
            <div id="loading_container2">
              <div id="loading_container3">
                <div id="loading_container4">
                  Loading, please wait...
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "ribbon"){ ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo ucfirst(htmlspecialchars($_GET["page"])); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo ucfirst(htmlspecialchars($_GET["page"])); ?></li>
      </ol>
    </section>

    <hr>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">

        <div class ="container-fluid">
          <?php if($_SESSION['role'] != '5'){ ?>
          <button type="button" class="button" id="add_inputRIBBON">INPUT RIBBON</button>
          <button type="button" class="button" id="log_ribbon">History Stock</button>
          <?php } ?>
          <table id="tablenya" class="datatable" style="width:100%">
            <thead>
              <tr>
                  <th>No</th>
                  <th>Rak</th>
                  <th>Customer</th>
                  <th>Nama Barang</th>
                  <th>Ukuran</th>
                  <th>Fi/Fo</th>
                  <th>Stok</th>
                  <th>Diinput</th>
                  <th>Option</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

          <table id="tablenya2" class="datatable" style="display:none;width:100%">
            <caption>
              <div id="widthSortby" class="input-group">
                <span class="input-group-addon">Sort by </span>
                <select id="sortby" class="form-control"></select>
                <span class="input-group-btn">
                  <button type="button" id="LoadData" class="btn btn-default">View</button>
                </span>
              </div>
            </caption>
            <thead>
              <tr>
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>Rak</th>
                  <th>Nama</th>
                  <th>No S.Jalan</th>
                  <th>No PO</th>
                  <th>Status</th>
                  <th>Nama Barang</th>
                  <th>Ukuran</th>
                  <th>Fi/Fo</th>
                  <th>Gulungan</th>
                  <th>Stok Awal</th>
                  <th>Stok Masuk</th>
                  <th>Stok Keluar</th>
                  <th>Stok Akhir</th>
                  <th>Diinput</th>
                  <th>Option</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>

          <!-- The Modal -->
          <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT RIBBON</h2>
              <form class="form add" id="form_inputRIBBON" data-id="" novalidate>

                <div class="form-group rak">
                  <label for="rak">Rak:</label>
                  <input type="text" class="form-control" name="rak" id="rak" required>
                </div>

                <div class="form-group customer">
                  <label for="customer">Customer:</label>
                  <input type="text" class="form-control" name="customer" id="customer" required>
                </div>

                <div class="form-group product">
                  <label for="product">Nama Barang:</label>
                  <input type="text" class="form-control" name="product" id="product" required>
                </div>

                <div class="form-group size">
                  <label for="name">Ukuran: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="size" id="size" value="" required>
                </div>

                <div class="form-group fi-fo">
                  <label for="fi-fo">Fi/Fo:</label>
                  <input type="text" class="form-control" name="fi-fo" id="fi-fo" required>
                </div>

                <div class="form-group tgl_" style="display: none">
                  <label for="tanggal">Tanggal: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="tgl_" id="tgl_" required>
                </div>

                <div class="form-group nosj_" style="display: none">
                  <label for="nosj">No Surat Jalan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="nosj_" id="nosj_" required>
                </div>

                <div class="form-group nopo_" style="display: none">
                  <label for="nopo">No PO: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="nopo_" id="nopo_" required>
                </div>

                <div class="form-group gulungan_" style="display: none">
                  <label for="gulungan">Gulungan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="gulungan_" id="gulungan_" required>
                </div>

                <div class="form-group stock" style="display: none">
                  <label for="Stock">Stok: <span class="required">*</span></label>
                  <input type="number" min="0" class="form-control" name="stock" id="stock" required>
                </div>

                <div class="form-group s_masuk" style="display: none">
                  <label for="s_masuk">Stok Masuk: <span class="required">*</span></label>
                  <input type="number" min="0" class="form-control" name="s_masuk" id="s_masuk" required>
                </div>

                <div class="form-group s_keluar" style="display: none">
                  <label for="s_keluar">Stok Keluar: <span class="required">*</span></label>
                  <input type="number" min="0" class="form-control" name="s_keluar" id="s_keluar" required>
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="saving">Simpan</button>
                  <input type="button" class="lightbox_close" value="Batal">
                </div>
              </form>
            </div>

          </div>

          <noscript id="noscript_container">
            <div id="noscript" class="error">
              <p>JavaScript support is needed to use this page.</p>
            </div>
          </noscript>

          <div id="message_container">
            <div id="message" class="success">
              <p>This is a success message.</p>
            </div>
          </div>

          <div id="loading_container">
            <div id="loading_container2">
              <div id="loading_container3">
                <div id="loading_container4">
                  Loading, please wait...
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "bahan_baku"){ ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo ucfirst(htmlspecialchars($_GET["page"])); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo ucfirst(htmlspecialchars($_GET["page"])); ?></li>
      </ol>
    </section>

    <hr>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">

        <div class ="container-fluid">
          <?php if($_SESSION['role'] != '5'){ ?>
          <button type="button" class="button" id="add_inputRIBBON">INPUT BAHAN BAKU</button>
          <button type="button" class="button" id="log_bahanbaku">History Stock</button>
          <?php } ?>
          <table id="tablenya" class="datatable" style="width:100%">
            <thead>
              <tr>
                  <th>No</th>
                  <th>Ukuran</th>
                  <th>Bahan</th>
                  <th>Warna</th>
                  <th>Keterangan</th>
                  <th>Satuan</th>
                  <th>Stok</th>
                  <th>Diinput</th>
                  <th>Option</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

          <table id="tablenya2" class="datatable" style="display:none;width:100%">
            <caption>
              <div id="widthSortby" class="input-group">
                <span class="input-group-addon">Sort by </span>
                <select id="sortby" class="form-control"></select>
                <span class="input-group-btn">
                  <button type="button" id="LoadData" class="btn btn-default">View</button>
                </span>
              </div>
            </caption>
            <thead>
              <tr>
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>Size</th>
                  <th>Bahan</th>
                  <th>Warna</th>
                  <th>Customer</th>
                  <th>No PO</th>
                  <th>Status</th>
                  <th>Ukuran</th>
                  <th>Keterangan</th>
                  <th>Satuan</th>
                  <th>Stok Awal</th>
                  <th>Stok Masuk</th>
                  <th>Stok Keluar</th>
                  <th>Stok Akhir</th>
                  <th>Diinput</th>
                  <th>Option</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>

          <!-- The Modal -->
          <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT BAHAN BAKU</h2>
              <form class="form add" id="form_inputRIBBON" data-id="" novalidate>

                <div class="form-group date" style="display: none">
                  <label for="date">Tanggal: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="date" id="date" required>
                </div>

                <div class="form-group customer" style="display: none">
                  <label for="customer">Customer: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="customer" id="customer" required>
                </div>

                <div class="form-group nopo" style="display: none">
                  <label for="nopo">No PO: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="nopo" id="nopo" required>
                </div>

                <div class="form-group ukuran" style="display: none">
                  <label for="ukuran">Ukuran: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="ukuran" id="ukuran" required>
                </div>

                <div class="form-group size">
                  <label for="size">Ukuran: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="size" id="size" value="" required>
                </div>

                <div class="form-group ingredient">
                  <label for="ingredient">Bahan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="ingredient" id="ingredient" value="" required>
                </div>

                <div class="form-group color">
                  <label for="color">Warna: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="color" id="color" value="" required>
                </div>
                
                <div class="form-group note">
                  <label for="note">Keterangan:</label>
                  <textarea type="text" class="form-control" name="note" id="note"></textarea>
                </div>

                <div class="form-group stock">
                  <label for="stock">Stok: <span class="required">*</span></label>
                  <input type="number" min="0" class="form-control" name="stock" id="stock" required>
                </div>

                <div class="form-group s_masuk" style="display: none">
                  <label for="s_masuk">Stok Masuk: <span class="required">*</span></label>
                  <input type="number" min="0" class="form-control" name="s_masuk" id="s_masuk" required>
                </div>

                <div class="form-group s_keluar" style="display: none">
                  <label for="s_keluar">Stok Keluar: <span class="required">*</span></label>
                  <input type="number" min="0" class="form-control" name="s_keluar" id="s_keluar" required>
                </div>

                <div class="form-group unit">
                  <label for="unit">Satuan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="unit" id="unit" required>
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="saving">Simpan</button>
                  <input type="button" class="lightbox_close" value="Batal">
                </div>
              </form>
            </div>

          </div>

          <noscript id="noscript_container">
            <div id="noscript" class="error">
              <p>JavaScript support is needed to use this page.</p>
            </div>
          </noscript>

          <div id="message_container">
            <div id="message" class="success">
              <p>This is a success message.</p>
            </div>
          </div>

          <div id="loading_container">
            <div id="loading_container2">
              <div id="loading_container3">
                <div id="loading_container4">
                  Loading, please wait...
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "delivery_orders_delivery"){ ?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo ucfirst(htmlspecialchars($_GET["page"])); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo ucfirst(htmlspecialchars($_GET["page"])); ?></li>
      </ol>
    </section>

    <hr>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class ="container-fluid">
          <button class="button function_undo" style="display: none">Kembali</button>
          <table id="tablenya" class="datatable" style="width:100%">
            <caption>
              <div id="widthSortby" class="input-group">
                <span class="input-group-addon">Sort by </span>
                <select id="sortby" class="form-control"></select>
                <span class="input-group-btn">
                  <button type="button" id="LoadData" class="btn btn-default">View</button>
                </span>
              </div>
            </caption>
            <thead>
              <tr>
                  <th>Tgl Surat Jalan</th>
                  <th>Surat Jalan</th>
                  <th>Customer</th>
                  <th>No PO</th>
                  <th>No SO</th>
                  <th>Ship To</th>
                  <th>Nama Barang</th>
                  <th>Qty</th>
                  <th>Satuan</th>
                  <th>Kurir</th>
                  <th>No Resi</th>
                  <th>Ongkir</th>
                  <th>Diinput</th>
                  <th>Option</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

          <!-- The Modal -->
          <div id="myModal2" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              <h2 class="FormTitle" style="text-align: center">PRATINJAU PRINT</h2>
              <form class="form add" id="form_print" data-id="" novalidate>

                <hr>
                <p style="font-weight: bold">DITERBITKAN</p>
                
                <div class="form-group">
                  <label for="sj_date">Tanggal: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="sj_date" id="sj_date" required readonly>
                </div>
        
                <div class="form-group">
                  <label for="no_po_pratinjau">No Po: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="no_po_pratinjau" id="no_po_pratinjau" readonly>
                </div>
        
                <div class="form-group">
                  <label for="no_delivery">No Surat Jalan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="no_delivery" id="no_delivery" required readonly>
                </div>
        
                <hr>
                <p style="font-weight: bold">RINCIAN TAGIHAN</p>
                <div class="form-group">
                  <label for="customer">Customer: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="custom" id="custom" required readonly>
                </div>

                <div class="form-group shipto">
                  <label for="phone">Note:</label>
                  <input type="text" class="form-control" name="phone" id="telp" required>
                </div>

                <div class="form-group shipto">
                  <label for="shipto">Ship to:</label>
                  <textarea class="form-control" name="shipto" id="shipto" minlength="15"></textarea>
                </div>

                <hr>
                <p style="font-weight: bold">RINCIAN PENGIRIMAN</p>
                <div class="itemnya"></div>
        
                <hr>
                <p style="font-weight: bold">PENANGGUNG JAWAB</p>

                <div class="form-group">
                  <label for="ttd">Nama: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="ttd" id="ttd" value="<?php echo $_SESSION['name'];?>" required readonly>
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="print" onclick="tampilkanNilai()">Cetak</button>
                  <input type="button" class="lightbox_close" value="Batal">
                  <input type="hidden" class="data-id" value="">
                </div>

                <script>
                    function tampilkanNilai() {
                        // Ambil nilai dari input
                        var nilai = document.getElementById("telp").value;

                        // Masukkan nilai ke dalam elemen span
                        document.getElementById("nilaiTampil").innerText = nilai;
                    }
                </script>

              </form>
            </div>
          </div>

          <!-- Modal Ongkir -->
          <div id="PeriodeResult" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close periode_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT PERIODE</h2>
              <form class="form add" id="form_periode" data-id="" novalidate>

                <div class="form-group">
                  <label>Dari Tanggal: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="startdate" id="startdate" required>
                </div>

                <div class="form-group">
                  <label>Sampai: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="enddate" id="enddate" required>
                </div>
        
                <div class="button_container" style="text-align: center">
                  <button type="submit" class="lihat">View</button>
                  <input type="button" class="periode_close" value="Cancel">
                  <input type="hidden" id="report" name="report" value="periode">
                </div>
              </form>
            </div>
          </div>

          <div id="PrintModal" class="modal">
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              <br><hr>
              <div class="printnow">
                <div class="row">
                  <div class="col-xs-12">
                    <div class="row delivery-orders-title"></div>
                    <div class="row">
                      <div class="col-xs-12">
                        <p class="text-center"><strong>DELIVERY NOTE</strong></p>
                      </div>
                    </div>
                    <div class="row" style="font-size: 12px">
                      <div class="col-md-9 col-xs-9">
                        <p><strong>Bill To : </strong><span class="bill"></span></p>
                        <p><strong>Ship to : </strong><span class="ship"></span></p>
                      </div>
                      <div class="col-md-3 col-xs-3">
                          <p><strong>Date : </strong><span class="tgl"></span></p>
                          <p><strong>Delivery Order No : </strong><span class="nosj"></span></p>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row" style="font-size: 12px">
                  <div class="col-md-12">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th class="text-center">NO</th>
                          <th class="text-center">NO SO</th>
                          <th class="text-center">NO PO</th>
                          <th class="text-center" style="width:250px">ITEM</th>
                          <th class="text-center">QTY</th>
                          <th class="text-center">UNIT</th>
                        </tr>
                      </thead>
                      <tbody class="tbody"></tbody>
                    </table>
                  </div>
                </div>

                <div class="row" style="font-size: 12px">
                  <div class="col-md-9 col-xs-9">
                    <p>
                      <strong>Prepared by</strong>
                    </p>
                    <p class="ttd"></p>
                    <p class="ttd_date"></p>
                  </div>
                  <div class="col-md-3 col-xs-3" style="margin-left: -290px;">
                    <p><strong>Note : </strong><span id="nilaiTampil" class="phone"></span></p>
                  </div>
                  <div class="col-md-3 col-xs-3">
                    <p>
                      <strong>Received by</strong>
                    </p>
                    <p>
                      <strong>Name :</strong>
                    </p>
                    <p>
                      <strong>Date :</strong>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <noscript id="noscript_container">
            <div id="noscript" class="error">
              <p>JavaScript support is needed to use this page.</p>
            </div>
          </noscript>

          <div id="message_container">
            <div id="message" class="success">
              <p>This is a success message.</p>
            </div>
          </div>

          <div id="loading_container">
            <div id="loading_container2">
              <div id="loading_container3">
                <div id="loading_container4">
                  Loading, please wait...
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "delivery_orders_waiting"){ ?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo ucfirst(htmlspecialchars($_GET["page"])); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo ucfirst(htmlspecialchars($_GET["page"])); ?></li>
      </ol>
    </section>

    <hr>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class ="container-fluid">
          <table id="tablenya" class="datatable" style="width:100%">
            <thead>
              <tr>
                  <th>Tgl SPK</th>
                  <th>Customer</th>
                  <th>No PO</th>
                  <th>No SO</th>
                  <th>Tenggat Waktu</th>
                  <th>Option</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

          <!-- The Modal -->
          <div id="myModal" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT Surat jalan</h2>
              <form class="form add" id="form_inputSJ" data-id="" novalidate>

                <div class="form-group spk_date">
                  <label for="spk_date">Tgl SPK: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="spk_date" id="spk_date" readonly>
                </div>

                <div class="form-group customer">
                  <label for="customer">Customer: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="customer" id="customer" readonly>
                  <input type="hidden" name="validasi" id="validasi" value="0">
                </div>

                <div class="form-group po_customer">
                  <label for="po_customer">No PO: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="po_customer" id="po_customer" readonly>
                </div>

                <div class="form-group no_sj">
                  <label for="no_sj">No Surat Jalan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="no_sj" id="no_sj" value="" readonly>
                </div>
                
                <div class="form-group sj_date">
                  <label for="tanggal">Tgl Surat Jalan: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="sj_date" id="sj_date" value="<?php echo date('Y-m-d'); ?>" required>
                </div>

                <div class="form-group shipto">
                  <label for="shipto">Ship to: <span class="required">*</span></label>
                  <textarea class="form-control" name="shipto" id="shipto" minlength="15" required></textarea>
                </div>

                <div class="datanyanih"></div><hr>

                <div class="form-group courier">
                  <label for="nama_kurir">Courier Name: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="courier" id="courier" required>
                </div>

                <div class="form-group resi">
                  <label for="no_resi">No Tracking:</label>
                  <input type="text" class="form-control" name="resi" id="resi">
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="saving">Simpan</button>
                  <input type="button" class="lightbox_close" value="Cancel">
                </div>
              </form>
            </div>
          </div>

          <noscript id="noscript_container">
            <div id="noscript" class="error">
              <p>JavaScript support is needed to use this page.</p>
            </div>
          </noscript>

          <div id="message_container">
            <div id="message" class="success">
              <p>This is a success message.</p>
            </div>
          </div>

          <div id="loading_container">
            <div id="loading_container2">
              <div id="loading_container3">
                <div id="loading_container4">
                  Loading, please wait...
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "cashflow"){ ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo ucfirst(htmlspecialchars($_GET["page"])); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo ucfirst(htmlspecialchars($_GET["page"])); ?></li>
      </ol>
    </section>

    <hr>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">

        <div class ="container-fluid">
          <?php if($_SESSION['role'] != '5'){ ?>
            <button type="button" class="button" id="KasMasuk">KAS MASUK</button>
            <button type="button" class="button" id="KasKeluar">KAS KELUAR</button>
          <?php } ?>
          <table id="tablenya" class="datatable" style="width:100%">
            <caption>
              <div id="widthSortby" class="input-group">
                <span class="input-group-addon">Sort by </span>
                <select id="sortby" class="form-control"></select>
                <span class="input-group-btn">
                  <button type="button" id="LoadData" class="btn btn-default">View</button>
                </span>
              </div>
            </caption>

            <thead>
              <tr>
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>Nama</th>
                  <th>Tujuan</th>
                  <th>Keterangan</th>
                  <th>Uang Masuk</th>
                  <th>Uang Keluar</th>
                  <th>Sisa Saldo</th>
                  <th>Option</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th class="text-right" style="font-weight: bold">TOTAL :</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
              </tr>
            </tfoot>
          </table>

          <!-- The Modal -->
          <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center"></h2>
              <form class="form add" id="form_inputKAS" data-id="" novalidate>

                <div class="form-group tgl">
                  <label for="tgl">Tanggal: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="tgl" id="tgl" value="<?php echo date('Y-m-d'); ?>" required>
                </div>

                <div class="form-group nama">
                  <label for="nama">Nama: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="nama" id="nama" value="<?php echo $_SESSION['name']; ?>" required>
                </div>

                <div class="form-group tujuan">
                  <label for="tujuan">Tujuan:</label>
                  <input type="text" class="form-control" name="tujuan" id="tujuan">
                </div>

                <div class="form-group keterangan">
                  <label for="keterangan">Keterangan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="keterangan" id="keterangan" required>
                </div>

                <div class="form-group uang">
                  <label for="uang">Uang: <span class="required">*</span></label>
                  <input type="text" placeholder="0" class="form-control" name="uang" id="uang" required>
                </div>

                <div class="form-group type" style="display: none">
                  <label for="type">Type: <span class="required">*</span></label>
                  <input type="number" min="0" class="form-control" name="type" id="type" required readonly>
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="saving">Simpan</button>
                  <input type="button" class="lightbox_close" value="Batal">
                </div>
              </form>
            </div>

          </div>

          <noscript id="noscript_container">
            <div id="noscript" class="error">
              <p>JavaScript support is needed to use this page.</p>
            </div>
          </noscript>

          <div id="message_container">
            <div id="message" class="success">
              <p>This is a success message.</p>
            </div>
          </div>

          <div id="loading_container">
            <div id="loading_container2">
              <div id="loading_container3">
                <div id="loading_container4">
                  Loading, please wait...
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "tol"){ ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo ucfirst(htmlspecialchars($_GET["page"])); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo ucfirst(htmlspecialchars($_GET["page"])); ?></li>
      </ol>
    </section>

    <hr>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">

        <div class ="container-fluid">
          <?php if($_SESSION['role'] != '5'){ ?>
            <button type="button" class="button" id="KasMasuk">DEBIT</button>
            <button type="button" class="button" id="KasKeluar">KREDIT</button>
          <?php } ?>
          <table id="tablenya" class="datatable" style="width:100%">
            <caption>
              <div id="widthSortby" class="input-group">
                <span class="input-group-addon">Sort by </span>
                <select id="sortby" class="form-control"></select>
                <span class="input-group-btn">
                  <button type="button" id="LoadData" class="btn btn-default">View</button>
                </span>
              </div>
            </caption>
            <thead>
              <tr>
                  <th>no</th>
                  <th>Tanggal</th>
                  <th>Pintu TOL</th>
                  <th>Debet</th>
                  <th>Kredit</th>
                  <th>Sisa Saldo</th>
                  <th>Option</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th class="text-right" style="font-weight: bold">TOTAL :</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
          </table>

          <!-- The Modal -->
          <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center"></h2>
              <form class="form add" id="form_inputKAS" data-id="" novalidate>

                <div class="form-group tgl">
                  <label for="tgl">Tanggal: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="tgl" id="tgl" value="<?php echo date('Y-m-d'); ?>" required>
                </div>

                <div class="form-group keterangan">
                  <label for="keterangan">Pintu TOL: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="keterangan" id="keterangan" required>
                </div>

                <div class="form-group uang">
                  <label for="uang">Uang: <span class="required">*</span></label>
                  <input type="text" placeholder="0" class="form-control" name="uang" id="uang" required>
                </div>

                <div class="form-group type" style="display: none">
                  <label for="type">Type: <span class="required">*</span></label>
                  <input type="number" min="0" class="form-control" name="type" id="type" required readonly>
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="saving">Simpan</button>
                  <input type="button" class="lightbox_close" value="Batal">
                </div>
              </form>
            </div>

          </div>

          <noscript id="noscript_container">
            <div id="noscript" class="error">
              <p>JavaScript support is needed to use this page.</p>
            </div>
          </noscript>

          <div id="message_container">
            <div id="message" class="success">
              <p>This is a success message.</p>
            </div>
          </div>

          <div id="loading_container">
            <div id="loading_container2">
              <div id="loading_container3">
                <div id="loading_container4">
                  Loading, please wait...
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "setting"){ ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo ucfirst(htmlspecialchars($_GET["page"])); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo ucfirst(htmlspecialchars($_GET["page"])); ?></li>
      </ol>
    </section>

    <hr>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class ="container-fluid">

          <form class="form" id="BANK" novalidate>
            <div class="form-group">
              <h4>Bank</h4>
            </div>

            <?php
            require '../utils/connectV1.php';
            $query = "SELECT isi FROM setting WHERE ket = 'BANK'";
            $sql = $connect->query($query);
            if($sql->num_rows < 1){ ?>

            <div class="form-group bank">
              <label for="Bank">Nama Bank: <span class="required">*</span></label>
              <input type="text" class="form-control" name="data[bank][]" id="bank" placeholder="BCA" required>
            </div>

            <div class="form-group norek">
              <label for="norek">No Rekening: <span class="required">*</span></label>
              <input type="text" class="form-control" name="data[norek][]" id="norek" placeholder="1662687777" required>
            </div>

            <div class="form-group atasnama">
              <label for="atasnama">Atas Nama: <span class="required">*</span></label>
              <input type="text" class="form-control" name="data[atasnama][]" id="atasnama" placeholder="PT. ABC Oke Oce" required>
            </div>

            <?php } else {
              $barisN = 0;
              while($row = $sql->fetch_array()){
                $barisN = $barisN + 1;
                $ex = explode('-', $row['isi']); ?>

                <div class="looping_bank" id="looping-<?php echo $barisN; ?>" >
                  <hr class="looping-bank">
                  <p><button type="button" name="remove" idx="<?php echo $barisN; ?>" class="btn btn-danger btn_remove">Hapus</button></p>

                  <div class="form-group bank">
                    <label for="Bank">Nama Bank: <span class="required">*</span></label>
                    <input type="text" class="form-control" name="data[bank][]" id="bank" value="<?php echo $ex[0]; ?>" placeholder="BCA" required>
                  </div>

                  <div class="form-group norek">
                    <label for="norek">No Rekening: <span class="required">*</span></label>
                    <input type="text" class="form-control" name="data[norek][]" id="norek" value="<?php echo $ex[1]; ?>" placeholder="1662687777" required>
                  </div>

                  <div class="form-group atasnama">
                    <label for="atasnama">Atas Nama: <span class="required">*</span></label>
                    <input type="text" class="form-control" name="data[atasnama][]" id="atasnama" value="<?php echo $ex[2]; ?>" placeholder="PT. ABC Oke Oce" required>
                  </div>
                </div>

              <?php }
            } ?>

            <div id="looping_bank"></div>

            <div class="button_container" style="text-align: center">
              <button type="button" class="tambah_bank">Tambah Bank</button>
              <button type="submit" class="simpanBANK">Simpan</button>
            </div>

          </form>

          <form class="form" id="API" novalidate>
            <div class="form-group">
              <h4>Server</h4>
            </div>
            <hr>
            <div class="form-group email">
              <label for="email">Email:</label>
              <?php
              if(!file_exists('../api.info')){
                echo '<input type="email" class="form-control" name="email" id="email" required>';
              } else {
                $get = file_get_contents('../api.info', true);
                $arr = explode("\n", $get);
                if(empty($arr[0])){
                  echo '<input type="email" class="form-control" name="email" id="email" required>';
                } else {
                  echo '<input type="email" class="form-control" name="email" id="email" value="'.$arr[0].'" required>';
                }
              }
              ?>
            </div>

            <div class="form-group password">
              <label for="password">Password:</label>
              <?php
              if(!file_exists('../api.info')){
                echo '<input type="password" class="form-control" name="password" id="password" required>';
              } else {
                $get = file_get_contents('../api.info', true);
                $arr = explode("\n", $get);
                if(empty($arr[1])){
                  echo '<input type="password" class="form-control" name="password" id="password" required>';
                } else{ 
                  echo '<input type="password" class="form-control" name="password" id="password" value="'.$arr[1].'" required>';
                }
              }
              ?>
            </div>

            <div class="form-group url">
              <label for="url">API Url:</label>
              <?php
              if(!file_exists('../api.info')){
                echo '<input type="text" class="form-control" name="url" id="url" required>';
              } else {
                $get = file_get_contents('../api.info', true);
                $arr = explode("\n", $get);

                if(empty($arr[2])){
                  echo '<input type="text" class="form-control" name="url" id="url" required>';
                } else{ 
                  echo '<input type="text" class="form-control" name="url" id="url" value="'.$arr[2].'" required>';
                }
              }
              ?>
            </div>

            <div class="button_container" style="text-align: center">
              <button type="button" class="sincData">Sinkronisasi</button>
              <button type="submit" class="simpanURL">Simpan</button>
            </div>
          </form>

          <form class="form" id="items" novalidate>
            <div class="form-group">
              <h4>Item List</h4>
            </div>
            <hr>
            <div class="button_container btn_item">
              <button type="button" class="item_view">View Item</button>
            </div>
          </form>

          <table id="item_table" class="datatable" style="width:100%;display: none">
            <thead>
              <tr>
                  <th>No</th>
                  <th>Item</th>
                  <th>Type</th>
                  <th>Field Input</th>
                  <th>Field Print</th>
                  <th>Option</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

          <form class="form" id="LOG" novalidate>
            <div class="form-group">
              <h4>Activity Log</h4>
            </div>
            <hr>
            <div class="button_container btn-container_log">
              <button type="button" class="viewLog">View log</button>
            </div>
          </form>

          <table id="tablenya" class="datatable" style="width:100%;display: none">
            <thead>
              <tr>
                  <th>No</th>
                  <th>User</th>
                  <th>Date</th>
                  <th>Query</th>
                  <th>Data</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

          <!-- The Modal -->
          <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center"></h2>
              <form class="form add" id="form_input" data-id="" novalidate>

                <div class="form-group item">
                  <label for="item">Item: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="item" id="item" required>
                </div>

                <div class="form-group type">
                  <label for="type">Type: <span class="required">*</span></label>
                  <select class="form-control" name="type" id="type" required>
                    <option value="" disabled selected>Pilih tipe:</option>
                    <option value="SO_ITEM">Sales Order</option>
                    <option value="PO_ITEM">Purchase Order</option>
                  </select>
                </div>

                <div class="form-group attribute_input">
                  <label for="attribute_input">Attribute Input: <em class="label label-danger">price and qty ( default )</em></label>
                  <input type="text" class="form-control" name="attribute_input" id="attr_input" required>
                </div>

                <div class="form-group input_list">
                  <label for="input_list">Attribute List:</label>
                  <textarea class="form-control" name="input_list" id="input_list" disabled></textarea>
                </div>

                <div class="form-group attribute_print">
                  <label for="attribute_print">Attribute Print:</label>
                  <input type="text" class="form-control" name="attribute_print" id="attr_print" required>
                </div>

                <div class="form-group print_list">
                  <label for="print_list">Attribute List:</label>
                  <textarea class="form-control" name="print_list" id="print_list" disabled></textarea>
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="saving">Simpan</button>
                  <input type="button" class="lightbox_close" value="Batal">
                </div>
              </form>
            </div>
          </div>

          <noscript id="noscript_container">
            <div id="noscript" class="error">
              <p>JavaScript support is needed to use this page.</p>
            </div>
          </noscript>

          <div id="message_container">
            <div id="message" class="success">
              <p>This is a success message.</p>
            </div>
          </div>

          <div id="loading_container">
            <div id="loading_container2">
              <div id="loading_container3">
                <div id="loading_container4">
                  Loading, please wait...
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php } elseif(!empty($_GET["page"]) AND htmlspecialchars($_GET["page"]) == "purchase"){ ?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo ucfirst(htmlspecialchars($_GET["page"])); ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo ucfirst(htmlspecialchars($_GET["page"])); ?></li>
      </ol>
    </section>

    <hr>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class ="container-fluid">
          <?php if($_SESSION['role'] != '5'){ ?>
            <button type="button" class="button" id="create_po">Create New</button>
            <button type="button" class="button" id="add_item_po">Add Item PO</button>
          <?php } ?>

          <table id="tablenya" class="datatable" style="width:100%">
            <caption>
              <div id="widthSortby" class="input-group">
                <span class="input-group-addon">Sort by </span>
                <select id="sortby" class="form-control"></select>
                <span class="input-group-btn">
                  <button type="button" id="LoadData" class="btn btn-default">View</button>
                </span>
              </div>
            </caption>
            <thead>
              <tr>
                  <th>Date</th>
                  <th>Company</th>
                  <th>Vendor</th>
                  <th>PO No</th>
                  <th>Purchase Order</th>
                  <th>Detail</th>
                  <th>Size</th>
                  <th>Price</th>
                  <th>Price/Roll</th>
                  <th>Qty</th>
                  <th>Unit</th>
                  <th>Merk</th>
                  <th>Type</th>
                  <th>Core</th>
                  <th>Gulungan</th>
                  <th>bahan</th>
                  <th>Note</th>
                  <th>Subtotal</th>
                  <th>Tax</th>
                  <th>Total</th>
                  <th>Diinput</th>
                  <th>Option</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th class="text-right" style="font-weight: bold">Total Amount :</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
              </tr>
            </tfoot> 
          </table>

          <!-- Modal Ongkir -->
          <div id="PeriodeResult" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close periode_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">INPUT PERIODE</h2>
              <form class="form add" id="form_periode" data-id="" novalidate>

                <div class="form-group">
                  <label>Dari Tanggal: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="startdate" id="startdate" required>
                </div>

                <div class="form-group">
                  <label>Sampai: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="enddate" id="enddate" required>
                </div>
        
                <div class="button_container" style="text-align: center">
                  <button type="submit" class="lihat">View</button>
                  <input type="button" class="periode_close" value="Cancel">
                  <input type="hidden" id="report" name="report" value="periode">
                </div>
              </form>
            </div>
          </div>
          
          <!-- The Modal -->
          <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              
              <h2 class="FormTitle" style="text-align: center">CREATE PURCHASE ORDER</h2>
              <form class="form add" id="form_inputPO" data-id="" novalidate>

                <div class="form-group company">
                  <label for="company">Company: <span class="required">*</span></label>
                  <select class="form-control" name="companyid" id="company" required></select>
                </div>

                <div class="form-group po_number" style="display: none">
                  <label for="po_number">PO Number: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="po_number" id="po_number" required>
                  <input type="hidden" name="fkid" id="fkid" value="">
                </div>

                <div class="form-group vendor">
                  <label for="vendor">Vendor Name: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="vendor" id="vendor" required>
                  <input type="hidden" name="vendorid" id="id_vendor" value="">
                </div>

                <div class="form-group po_date">
                  <label for="PO Date">Date: <span class="required">*</span></label>
                  <input type="date" class="form-control" name="po_date" id="po_date" value="<?php echo date('Y-m-d'); ?>" required>
                </div>

                <div class="form-group po_vendor" style="display: none">
                  <label for="PO vendor">No PO: </label>
                  <input type="text" class="form-control" name="po_vendor" id="po_vendor" value="">
                </div>

                <div class="form-group po_type">
                  <label for="purchase">Purchase Order Type: <span class="required">*</span></label>
                  <select class="form-control" id="po_type" required></select>
                  <input type="hidden" class="po_type" name="po_type" value="" />
                </div>

                <hr class='header-item'>
                
                <p>
                  <button type="button" class="btn btn-primary tambah_barang" style="display: none">Tambah barang</button>
                </p>

                <div class="form-group detail" style="display: none">
                  <label for="detail">Detail: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[detail][]" id="detail" value="" required>
                </div>

                <div class="form-group size" style="display: none">
                  <label for="Size">Size: <span class="required">*</span></label>
                  <input type="number" class="form-control sizeval_1" name="data[size][]" id="size" value="" required>
                </div>

                <div class="form-group merk" style="display: none">
                  <label for="merk">Merk: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[merk][]" id="merk" required>
                </div>

                <div class="form-group type" style="display: none">
                  <label for="type">Type: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[type][]" id="type" required>
                </div>

                <div class="form-group core" style="display: none">
                  <label for="core">Core: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[core][]" id="core" required>
                </div>

                <div class="form-group gulungan" style="display: none">
                  <label for="gulungan">Gulungan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[roll][]" id="gulungan" required>
                </div>

                <div class="form-group bahan" style="display: none">
                  <label for="bahan">Bahan: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[material][]" id="bahan" required>
                </div>

                <div class="form-group price_1">
                  <label for="price_1">Price: <span class="required">*</span></label>
                  <input type="text" class="form-control price1val_1" name="data[price_1][]" id="price_1" required>
                </div>

                <div class="form-group price_2" style="display: none">
                  <label for="price_2">Price (Secondary): <span class="required">*</span> <em class="label label-success">Size x Price = Price (secondary)</em></label>
                  <div class="row">
                    <div class="col-md-10">
                      <input type="text" class="form-control price2val_1" name="data[price_2][]" id="price_2" required>
                    </div>
                    <div class="col-md-2">
                      <button type="button" class="btn btn-primary hitung_1">Hitung</button>
                    </div>
                  </div>
                </div>

                <div class="form-group qty">
                  <label for="qty">Qty: <span class="required">*</span></label>
                  <input type="number" min="0" class="form-control" name="data[qty][]" id="qty" required>
                </div>  

                <div class="form-group unit" style="display: none">
                  <label for="unit">Unit: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="data[unit][]" id="unit" required>
                </div>

                <div id="looping_barang"></div>
                
                <hr class='footer-item'>

                <div class="form-group note">
                  <label for="Note">Note:</label>
                  <textarea class="form-control" name="note" id="note"></textarea>
                </div>

                <div class="form-group ppns">
                  <label for="sel1">Tax: <span class="required">*</span></label></label>
                  <select class="form-control" name="tax" id="ppns" required>
                    <option value="" selected disabled>Menggunakan PPN 11% ?</option>
                    <option value="0">Tidak</option>
                    <option value="1">Ya</option>
                  </select>
                </div>

                <div class="form-group address" style="display: none">
                  <label for="address">Address:</label>
                  <textarea class="form-control" name="address" id="address"></textarea>
                </div>

                <div class="form-group tanda_tangan" style="display: none">
                  <label for="tanda_tangan">Tanda Tangan:</label>
                  <input type="text" class="form-control" name="tanda_tangan" id="tanda_tangan" required>
                </div>

                <div class="button_container" style="text-align: center">
                  <button type="submit" class="saving">Simpan</button>
                  <input type="button" class="lightbox_close" value="Batal">
                </div>
              </form>
            </div>
          </div>

          <div id="PrintModal" class="modal">
            <div class="modal-content">
              <span class="close lightbox_close">&times;</span>
              <br><hr>
              <div class="printnow">
                <div class= "page-header">
                  <div class="row">
                    <div class="col-xs-4 col-md-4">
                      <div class="logo_surat"></div>
                    </div>
                    <div class=" col-xs-8 col-md-8 text-center" style="letter-spacing: 2px;">
                      <h4 class="company_surat" style="margin-bottom: 0px"><strong></strong></h4>
                      <p class="alamat_surat" style="font-size: 12px;margin-bottom: 0px"></p>
                      <p class="telp_surat" style="font-size: 12px;margin-bottom: 0px"></p>
                      <h5 class="kepala_surat" style="margin-bottom: 0px"><strong>PURCHASE ORDER</strong></h5>
                    </div>
                  </div>
                </div>

                <div class="row" style="font-size: 12px;">
                  <div class="col-xs-6 col-md-6">
                    <p class="tgl_po" style="margin-bottom: 0px"></p>
                  </div>
                  <div class="col-xs-6 col-md-6">
                    <p class="penjual" style="margin-bottom: 0px"></p>
                  </div>
                  <div class="col-xs-6 col-md-6">
                    <p class="nomor"></p>
                  </div>
                  <div class="col-xs-6 col-md-6">
                    <p class="alamat"></p>
                  </div>
                </div>

                <table class="table table-bordered" style="font-size: 12px;">
                  <thead><tr class="thead"></tr></thead>
                  <tbody class="tbody"></tbody>
                  <tfoot>
                    <tr class='tfoot-heading'></tr>
                    <tr class='tfoot-value1'></tr>
                    <tr class='tfoot-value2'></tr>
                    <tr class='tfoot-value3'></tr>
                  </tfoot>
                </table>

                <div class="row" style="font-size: 12px;">
                  <div class="col-md-8"></div>
                  <div class="col-md-4 text-right">
                    <p class="ttd_tgl"></p>
                    <p class="ttd_person"></p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <noscript id="noscript_container">
            <div id="noscript" class="error">
              <p>JavaScript support is needed to use this page.</p>
            </div>
          </noscript>

          <div id="message_container">
            <div id="message" class="success">
              <p>This is a success message.</p>
            </div>
          </div>

          <div id="loading_container">
            <div id="loading_container2">
              <div id="loading_container3">
                <div id="loading_container4">
                  Loading, please wait...
                </div>
              </div>
            </div>
          </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php } else { ?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <hr>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">

      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php } ?>