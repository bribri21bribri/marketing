<?php
include __DIR__ . '/_connectDB.php';
try {
    $coupon_sql = "SELECT * FROM coupon_genre";
    $coupon_stmt = $pdo->query($coupon_sql);
    $coupon_rows = $coupon_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    echo $ex->getMessage();
}

try {
    $member_sql = "SELECT * FROM member_list";
    $member_stmt = $pdo->query($member_sql);
    $member_rows = $member_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $ex) {
    echo $ex->getMessage();
}
?>


<?php include __DIR__ . './_header.php';
include __DIR__ . './_navbar.php';
?>

<main class="col-10 bg-white">

  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active"><a href="#">coupon獲取紀錄查詢</a></li>
    </ol>
  </nav>

  <section class="container-fluid" style="height: 100%;">

    <div class="row py-2">
      <div class="col-md-10">
        <div class="alert alert-success" role="alert" style="display: none;" id="info_bar"></div>
      </div>

      <div class="col-md-2">
        <div class="">
          <select class="form-control" id="fetch_option_date">
            <option class="dropdown-item">列出所有Coupon</option>
            <option class="dropdown-item" data-sql="WHERE `coupon_expire`>`created_at`">列出有效期限內coupon</option>
            <option class="dropdown-item" data-sql="WHERE `coupon_expire`<`created_at`">列出已過期coupon</option>
          </select>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 table-responsive">
        <table id="coupon_table" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th scope="col">紀錄編號</th>
              <th scope="col">Coupon編號</th>
              <th scope="col">Coupon Code</th>
              <th scope="col">取得日期</th>
              <th scope="col">有效狀態</th>
              <th scope="col">使用者編號</th>
              <th scope="col">操作</th>
              <th scope="col"><input type="checkbox" id="select_all"></th>
            </tr>
          </thead>
          <tbody id="coupon_output">

          </tbody>
        </table>
      </div>
    </div>

    <!-- <div class="modal fade" id="userIdModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">新增獲取紀錄:指定使用者</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form name="insert_record_form" onsubmit="sendForm()">
            <div class="modal-body">

              <div class="form-group justify-content-center row">
                <label class="col-2 text-right"><span class="asterisk"> *</span>新增筆數</label>
                <div class="col-6">
                  <input type="text" class="form-control" name="record_amount" placeholder="輸入欲新增紀錄筆數">
                  <small class="form-text text-muted"></small>
                </div>
              </div>



              <div class="form-group justify-content-center row">
                <label class="col-2 text-right"><span class="asterisk"> *</span>coupon種類</label>
                <div class="col-6">
                  <select class="form-control" name="coupon_genre" id="coupon_genre">
                    <?php foreach ($coupon_rows as $coupon): ?>
                    <option value="<?=$coupon['camp_id']?>">
                      <?=$coupon['coupon_name']?>
                    </option>
                    <?php endforeach?>
                  </select>
                </div>
              </div>

              <div class="form-group justify-content-center row">
                <label class="col-2 text-right"><span class="asterisk"> *</span>使用者</label>
                <div class="col-6">
                  <select class="form-control" name="user_id" id="user_id">
                    <?php foreach ($member_rows as $member): ?>
                    <option value="<?=$member['mem_id']?>">
                      <?=$member['mem_account']?>
                    </option>
                    <?php endforeach?>
                  </select>
                </div>
              </div> -->

    <!-- <div class="form-group justify-content-center row">
                <label class="col-4 text-right"><span class="asterisk"> *</span>使用者</label>
                <div class="input-group col-8">
                  <input type="text" class="form-control" />
                  <div class="input-group-append">
                    <button type="button" class="btn btn-white dropdown-toggle dropdown-toggle-split"
                      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" style="overflow:scroll">
                      <?php foreach ($member_rows as $member): ?>
                      <li class="dropdown-item" href="#"><?=$member['mem_account']?></li>
                      <?php endforeach?>
                    </ul>
                  </div>
                </div>
              </div> -->

    <!-- </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" id="submit_btn" data-dismiss="modal">Submit</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
      </div>
    </div> -->

    <!-- <div class="modal fade" id="userLevelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <select class="form-control" name="issue_level" id="issue_level">
                <?php foreach ($mem_rows as $mem_row): ?>
                <option value="<?=$mem_row['mem_level']?>"><?=$mem_row['level_title']?></option>
                <?php endforeach;?>
              </select>

            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
            <button type="submit" class="btn btn-primary" data-dismiss="modal" id="submit_btn">配發</button>
          </div>
        </div>
      </div>
    </div> -->
    <script>
    function sendForm(e) {
      console.log('log')
      return false;
      const form = new FormData(insert_record_form);

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

    $(function() {

      function fetch_coupon() {
        $('#coupon_table').DataTable({
          drawCallback: function() {
            $('.switch').checkToggler({

              labelOn: "啟用",
              labelOff: "關閉"

            }).on('change click blur', function() {
              console.log('switch click')
            });
          },
          dom: 'lf<"#pagi-wrap.d-flex"<"mr-auto"B>p>t<"mt-3">',
          buttons: [{
              className: 'btn btn-primary ',
              attr: {
                id: 'coupon_insert_btn'
              },
              text: '新增coupon獲取紀錄',
              action: function() {
                window.location = './coupon_gain_insert.php'
              },
            },
            {
              className: 'btn btn-info',
              text: '新增獲取紀錄:指定使用者',
              action: function(e, dt, node, config) {

              },
              attr: {
                'data-toggle': 'modal',
                'data-target': '#userIdModal'
              }
            },
            {
              className: 'btn btn-info',
              text: '新增獲取紀錄:指定使用者等級',
              action: function(e, dt, node, config) {

              },
              attr: {
                'data-toggle': 'modal',
                'data-target': '#userLevelModal'
              }
            }
          ],
          "processing": true,
          "serverSide": true,
          "order": [],
          "ajax": {
            url: "coupon_gain_list_api.php",
            type: "POST",
            data: {

            }
          },
          "columnDefs": [{
              "targets": [7],
              "data": "gain_record_id",
              "render": function(data, type, row, meta) {
                return "<input data-coupon_record_id=" + data + " type='checkbox'>";
              }
            },
            {
              "targets": [6],
              "data": "gain_record_id",
              "render": function(data, type, row, meta) {
                return '<input data-coupon_record_id=' + data +
                  ' class="switch" type="checkbox" checked>';
              }
            },
          ],
          "columns": [{
              "data": "gain_record_id"
            },
            {
              "data": "coupon_genre_id"
            },
            {
              "data": "coupon_code"
            },
            {
              "data": "gain_date",
              "className": "text-truncate"
            },
            {
              "data": "coupon_valid",
              "render": function(data) {
                let display = ''
                if (data == 1) {
                  display = '啟用';
                } else if (data == 2) {
                  display = '關閉'
                }
                return display;
              }
            },
            {
              "data": "user_id"
            }
          ],
        })
      }
      fetch_coupon()
      //prepend 新增coupon 按鈕


      // $('#fetch_option_date').change(function() {
      //   let sql = $("#fetch_option_date option:selected").data('sql')
      //   $('#coupon_table').DataTable().destroy();
      //   fetch_coupon(sql)

      // })

      const info_bar = $("#info_bar");

      function delete_coupon() {
        let coupon_id = $(this).data('coupon_id');
        const form = new FormData();
        form.append("coupon_id", coupon_id);
        if (confirm(`確認是否刪除此筆coupon ID: ${coupon_id}`)) {
          fetch('coupon_delete_api.php', {
            method: "POST",
            body: form
          }).then(response => {
            return response.json()
          }).then(result => {
            console.log(result);

            info_bar.css("display", "block")
            if (result['success']) {
              info_bar.attr('class', 'alert alert-info').text('刪除成功');
            } else {
              info_bar.attr('class', 'alert alert-danger').text(result.errorMsg);
            }
            setTimeout(function() {
              info_bar.css("display", "none")
            }, 3000)

            $('#coupon_table').DataTable().destroy();
            fetch_coupon()
            $("#select_all").prop('checked', false)
          });

        }
      }
      $("#coupon_table tbody").on("click", ".del-btn", delete_coupon);

      function group_del(e, dt, node, config) {
        let form = new FormData();
        let delete_coupons = [];
        $('#coupon_table tbody :checked').each(function() {
          delete_coupons.push($(this).data('coupon_id'))
        });
        if (confirm('確認刪除資料')) {
          info_bar.css('display', 'block');
          if (delete_coupons.length < 1) {
            info_bar.attr('class', 'alert alert-danger');
            info_bar.html("未選擇資料");
            setTimeout(function() {
              info_bar.css('display', 'none')
            }, 3000);
            return false;
          } else {
            let delete_coupons_str = JSON.stringify(delete_coupons);
            form.append('delete_coupons', delete_coupons_str);
            fetch('_group_delete_api.php', {
                method: 'POST',
                body: form
              })
              .then(response => response.json())
              .then(data => {
                console.log(data);
                $('#coupon_table').DataTable().destroy();
                fetch_coupon(sql);
                info_bar.attr('class', 'alert alert-success');
                info_bar.html("刪除成功");
                setTimeout(function() {
                  info_bar.css('display', 'none')
                }, 3000);
              })
          }
        }
      }
    });
    </script>
    <?php include __DIR__ . './_footer.php'?>