<?php
require __DIR__ . '/_connectDB.php';
$campsite_sql = 'SELECT  * FROM campsite_list';
$stmt = $pdo->query($campsite_sql);
$campsite_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include __DIR__ . './_header.php';
include __DIR__ . './_navbar.php';
?>
<main class="col-10 bg-white">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="./coupon_list.php">優惠券查詢</a></li>
      <li class="breadcrumb-item active">新增優惠券</li>
    </ol>
  </nav>
  <section class="" style="height: 100%;">

    <!-- submit result message -->
    <div id="info_bar" style="display: none" class="alert alert-success"></div>
    <div class="container">
      <div class="card-body">

        <div class="row d-flex justify-content-center">
          <div class="col-sm-8">
            <h5 class="card-title text-center">新增優惠券</h5>
          </div>
        </div>
        <form method="POST" name="coupon_form" onsubmit="return sendForm()">

          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>優惠券名稱</label>
            <div class="col-6">
              <input type="text" class="form-control" name="coupon_name" placeholder="輸入coupon名稱">
              <small class="form-text text-muted"></small>
            </div>
          </div>

          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>折扣類型</label>
            <div class="col-6">
              <select class="form-control" name="discount_type" id="discount_type">
                <option value="percentage">打折</option>
                <option value="currency">扣除金額</option>
              </select>
            </div>
          </div>


          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>促銷數值</label>
            <div class="col-6">
              <input type="text" class="form-control" name="discount_unit" id="discount_unit" placeholder="輸入折扣數值">
            </div>
          </div>

          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>開始領取時間</label>
            <div class="col-6">
              <input type="date" class="form-control" id="avaliable_start" name="avaliable_start" value=""
                min="2018-01-01" max="">
            </div>
          </div>

          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>結束領取時間</label>
            <div class="col-6">
              <input type="date" class="form-control" id="avaliable_end" name="avaliable_end" value="" min="2018-01-01"
                max="">
            </div>
          </div>

          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>到期時間</label>
            <div class="col-6">
              <input type="date" class="form-control" id="coupon_expire" name="coupon_expire" value="" min="2018-01-01"
                max="">
            </div>
          </div>

          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>適用營區</label>
            <div class="col-6">
              <select class="form-control" name="camp_id" id="camp_id">
                <?php foreach ($campsite_rows as $campsite): ?>
                <option value="<?=$campsite['camp_id']?>">
                  <?=$campsite['camp_name']?>
                </option>
                <?php endforeach?>
              </select>
            </div>
          </div>

          <div class="form-group justify-content-center row">
            <label class="col-2 text-right">訂單價格條件</label>
            <div class="col-6">
              <input type="text" class="form-control" id="order_price" name="order_price" value=""
                placeholder="輸入此coupon適用之訂單最低金額">
            </div>
          </div>

          <div class="form-group justify-content-center row">
            <label class="col-2 text-right">訂單天數條件</label>
            <div class="col-6">
              <input type="text" class="form-control" id="order_night" name="order_night" value=""
                placeholder="輸入此coupon適用之訂單最低天數">
            </div>
          </div>

          <div class="form-group justify-content-center row">
            <label class="col-2 text-right">訂單人數條件</label>
            <div class="col-6">
              <input type="text" class="form-control" id="order_people" name="order_people" value=""
                placeholder="輸入此coupon適用之訂單最低人數">
            </div>
          </div>

          <div class="form-group justify-content-center row">
            <label class="col-2 text-right"><span class="asterisk"> *</span>優惠券描述</label>
            <div class="col-6">
              <textarea class="form-control" id="discription" name="discription" value=""></textarea>
            </div>
          </div>



          <div class="form-group justify-content-center row  text-center">
            <div class="col-sm-8">
              <button type="submit" class="btn btn-primary" id="submit_btn">新增</button>
            </div>
          </div>

        </form>
      </div>
    </div>
    <script>
    $(function() {
      $('#discount_type').on('click blur', function() {
        let discount_type = $(this).val()
        if (discount_type == 'percentage') {
          $('#discount_unit').attr('placeholder', '輸入折扣數值')
        } else if (discount_type == 'currency') {
          $('#discount_unit').attr('placeholder', '輸入折抵金額')
        }
      })


      //設定日期欄位最小日期為今日
      var today = new Date();
      var dd = today.getDate();
      var mm = today.getMonth() + 1; //January is 0!
      var yyyy = today.getFullYear();
      if (dd < 10) {
        dd = '0' + dd
      }
      if (mm < 10) {
        mm = '0' + mm
      }

      today = yyyy + '-' + mm + '-' + dd;
      $('#avaliable_start').attr('min', today)
      $('#avaliable_end').attr('min', today)
      $('#coupon_expire').attr('min', today)


    });
    const info_bar = document.getElementById('info_bar')

    function sendForm(e) {
      const form = new FormData(coupon_form);

      $('#submit_btn').attr('disabled', true);
      fetch('coupon_genre_insert_api.php', {
          method: 'POST',
          body: form
        })
        .then(response => response.json())
        .then(obj => {

          console.log(obj);

          info_bar.style.display = 'block';

          if (obj.success) {
            info_bar.className = 'alert alert-success';
            info_bar.innerHTML = '資料新增成功';
          } else {
            info_bar.className = 'alert alert-danger';
            info_bar.innerHTML = obj.errorMsg;
          }
          setTimeout(function() {
            info_bar.style.display = 'none';
          }, 3000);

          $('#submit_btn').attr('disabled', false);
        });
      return false;
    }
    </script>


    <?php include __DIR__ . './_footer.php';?>