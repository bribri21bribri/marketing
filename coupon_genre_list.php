<?php
include __DIR__ . '/_connectDB.php';

?>


<?php include __DIR__ . './_header.php';
include __DIR__ . './_navbar.php';
?>

<main class="col-10 bg-white">

  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active"><a href="#">coupon查詢</a></li>
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
            <option class="dropdown-item" data-sql="WHERE `coupon_expire`>CURRENT_DATE()">列出有效期限內coupon</option>
            <option class="dropdown-item" data-sql="WHERE `coupon_expire`<CURRENT_DATE()">列出已過期coupon</option>
          </select>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 table-responsive">
        <table id="coupon_table" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th scope="col">編號</th>
              <th scope="col">Coupon 名稱</th>
              <th scope="col">折扣數值</th>
              <th scope="col">折扣方法</th>
              <th scope="col">開始領取日期</th>
              <th scope="col">結束領取日期</th>
              <th scope="col">使用到期日期</th>
              <th scope="col">適用營地</th>
              <th scope="col">訂單價格條件</th>
              <th scope="col">訂單日數條件</th>
              <th scope="col">訂單人數條件</th>
              <th scope="col">描述</th>
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
            <h5 class="modal-title" id="exampleModalLabel"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-group">
                <label for="recipient-name" class="col-form-label">使用者ID</label>
                <input type="text" placeholder="輸入欲指派使用者ID" id="assign_by_id" class="form-control">
              </div>

            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal" id="group_assign_submit">配發</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="userLevelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal" id="issue_by_level_submit">配發</button>
          </div>
        </div>
      </div>
    </div> -->
    <script>
    $(function() {

      function fetch_coupon(sql) {
        $('#coupon_table').DataTable({

          dom: 'lf<"#pagi-wrap.d-flex"<"mr-auto"B>p>t<"mt-3">',

          buttons: [{
            className: 'btn btn-primary ',
            attr: {
              id: 'coupon_insert_btn'
            },
            text: '新增coupon',
            action: function() {
              window.location = './coupon_genre_insert.php'
            },

          }, ],
          "processing": true,
          "serverSide": true,
          "order": [],
          "ajax": {
            url: "coupon_genre_api.php",
            type: "POST",
            data: {
              date_condition: sql
            }
          },
          "columnDefs": [{
              "targets": [12],
              "data": "coupon_id",
              "render": function(data, type, row, meta) {
                return "<input data-coupon_id=" + data + " type='checkbox'>";
              }
            },
            {
              "targets": [13],
              "data": "coupon_id",
              "render": function(data, type, row, meta) {
                return '<a href="_edit_coupon.php?coupon_id=' + data +
                  '" class="edit_btn mx-1 p-1" data-coupon_id=' + data +
                  '><i class="fas fa-edit"></i></a > <a href="#" class="del-btn mx-1 p-1" data-coupon_id=' +
                  data + '><i class="fas fa-trash-alt"></i></a>';
              }
            },
          ],
          "columns": [{
              "data": "coupon_genre_id"
            },
            {
              "data": "coupon_name"
            },
            {
              "data": "discount_unit"
            },
            {
              "data": "discount_type",
              "render": function(data) {
                let display = ''
                if (data == 1) {
                  display = "折扣";
                } else if (data == 2) {
                  display = "扣除金額"
                }
                return display;
              }
            },
            {
              "data": "avaliable_start",
            },
            {
              "data": "avaliable_end",
            },
            {
              "data": "coupon_expire"
            },
            {
              "data": "camp_id",
            },
            {
              "data": "order_price"
            },
            {
              "data": "order_night"
            },
            {
              "data": "order_people"
            },
            {
              "data": "discription"
            },
          ],
        })
      }
      fetch_coupon()
      //prepend 新增coupon 按鈕



      $('#fetch_option_date').change(function() {
        let sql = $("#fetch_option_date option:selected").data('sql')
        $('#coupon_table').DataTable().destroy();
        fetch_coupon(sql)

      })

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