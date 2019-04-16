<?php
include __DIR__ . '/_connectDB.php';
try {
    $mem_sql = "SELECT * FROM member_level";
    $mem_stmt = $pdo->query($mem_sql);
    $mem_rows = $mem_stmt->fetchAll(PDO::FETCH_ASSOC);
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
      <li class="breadcrumb-item active"><a href="#">coupon查詢</a></li>
    </ol>
  </nav>

  <!-- <aside class="my-2">
  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link active" href="coupon_list.php">coupon查詢</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="coupon_insert.php">新增coupon</a>
    </li>
  </ul>
</aside> -->

  <section class="container-fluid" style="height: 100%;">

    <div class="row py-2">
      <div class="col-md-10">
        <div class="alert alert-success" role="alert" style="display: none;" id="info_bar"></div>
      </div>
      <!-- <div class="col-md-2">
        <div class="dropdown open">
          <button class="btn btn-secondary dropdown-toggle" type="button" id="coupon_list" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            列出Coupon
          </button>
          <select class="dropdown-menu" aria-labelledby="coupon_list" id="fetch_option">
            <option class="dropdown-item">列出所有Coupon</option>
            <option class="dropdown-item" data-sql=" `coupon_expire`>`created_at`">列出有效期限內coupon</option>
            <option class="dropdown-item" data-sql=" `coupon_expire`<`created_at`">列出已過期coupon</option>
          </select>
        </div>
      </div> -->
      <div class="col-md-2">
        <div class="">
          <select class="form-control" id="fetch_option_date">
            <option class="dropdown-item" data-sql="">列出所有Coupon</option>
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
              <th scope="col">編號</th>
              <th scope="col">Coupon 名稱</th>
              <th scope="col">Coupon Code</th>
              <th scope="col">建立</th>
              <th scope="col">折扣數值</th>
              <th scope="col">折扣方法</th>
              <th scope="col">發放條件</th>
              <th scope="col">是否有效</th>
              <th scope="col">到期</th>
              <th scope="col">使用者</th>
              <th scope="col">操作</th>
              <th scope="col"><input type="checkbox" id="select_all"></th>
            </tr>
          </thead>
          <tbody id="coupon_output">

          </tbody>
        </table>
      </div>
    </div>

    <div class="modal fade" id="userIdModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
    </div>
    <script>
    $(function() {

      function fetch_coupon(sql) {
        $('#coupon_table').DataTable({
          dom: 'lf<"#pagi-wrap.d-flex"p>t<"mt-3"B>',
          buttons: [{
              className: 'btn btn-danger ',
              attr: {
                id: 'group_del_btn'
              },
              text: '多筆刪除',
              action: group_del
            },
            {
              className: 'btn btn-info',
              text: '多筆指定:依使用者ID',
              action: function(e, dt, node, config) {

              },
              attr: {
                'data-toggle': 'modal',
                'data-target': '#userIdModal'
              }
            },
            {
              className: 'btn btn-info',
              text: '多筆指定:依使用者等級',
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
            url: "coupon_fetch.php",
            type: "POST",
            data: {
              date_condition: sql
            }
          },
          "columnDefs": [{
              "targets": [11],
              "data": "coupon_id",
              "render": function(data, type, row, meta) {
                return "<input data-coupon_id=" + data + " type='checkbox'>";
              }
            },
            {
              "targets": [10],
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
              "data": "coupon_id"
            },
            {
              "data": "coupon_name"
            },
            {
              "data": "coupon_code"
            },
            {
              "data": "created_at",
              "className": "text-truncate"
            },
            {
              "data": "dis_num"
            },
            {
              "data": "dis_type",
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
              "data": "issue_condi",
              "render": function(data) {
                let display = ''
                if (data == 1) {
                  display = "初次登入";
                } else if (data == 2) {
                  display = "會員升等"
                } else if (data == 3) {
                  display = '訂單累積';
                }
                return display;
              }
            },
            {
              "data": "coupon_valid"
            },
            {
              "data": "coupon_expire"
            },
            {
              "data": "user_id"
            },
          ],
        })
      }
      fetch_coupon()
      //prepend 新增coupon 按鈕
      $('#pagi-wrap').prepend(
        '<button class="btn btn-primary mr-auto"><a class="text-white" href="coupon_insert.php">新增coupon</a></button>'
      )


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


    // fetch_coupon();
    // const info_bar = $('#info_bar');

    // function fetch_coupon(sql) {
    //     const form = new FormData();
    //     form.append("sql", sql);
    //     fetch('coupon_list_api.php', {
    //         method: 'POST',
    //         body: form
    //     })
    //         .then(response => {
    //             return response.json()
    //         }).then(result => {
    //         // console.log(result.data);
    //         $('#coupon_table').DataTable({
    //             dom: 'lfrtipB',
    //             buttons: [
    //                 {
    //                     className: 'btn btn-danger',
    //                     text: '多筆刪除',
    //                     action: function (e, dt, node, config) {
    //                         let form = new FormData();
    //                         let delete_coupons = [];
    //                         $('#coupon_table tbody :checked').each(function () {
    //                             delete_coupons.push($(this).data('coupon_id'))
    //                         });
    //                         if (confirm('確認刪除資料')) {
    //                             info_bar.css('display', 'block');
    //                             if (delete_coupons.length < 1) {
    //                                 info_bar.attr('class', 'alert alert-danger');
    //                                 info_bar.html("未選擇資料");
    //                                 setTimeout(function () {
    //                                     info_bar.css('display', 'none')
    //                                 }, 3000);
    //                                 return false;
    //                             } else {
    //                                 let delete_coupons_str = JSON.stringify(delete_coupons);
    //                                 form.append('delete_coupons', delete_coupons_str);
    //                                 fetch('_group_delete_api.php', {
    //                                     method: 'POST',
    //                                     body: form
    //                                 })
    //                                     .then(response => response.json())
    //                                     .then(data => {
    //                                         console.log(data);
    //                                         $('#coupon_table').DataTable().destroy();
    //                                         fetch_coupon(sql);
    //                                         info_bar.attr('class', 'alert alert-success');
    //                                         info_bar.html("刪除成功");
    //                                         setTimeout(function () {
    //                                             info_bar.css('display', 'none')
    //                                         }, 3000);
    //                                     })
    //                             }
    //                         }
    //                     }
    //                 },
    //                 {
    //                     className: 'btn btn-info',
    //                     text: '多筆指定:依使用者ID',
    //                     action: function (e, dt, node, config) {

    //                     },
    //                     attr: {
    //                         'data-toggle': 'modal',
    //                         'data-target': '#userIdModal'
    //                     }
    //                 },
    //                 {
    //                     className: 'btn btn-info',
    //                     text: '多筆指定:依使用者等級',
    //                     action: function (e, dt, node, config) {

    //                     },
    //                     attr: {
    //                         'data-toggle': 'modal',
    //                         'data-target': '#userLevelModal'
    //                     }
    //                 }
    //             ],
    //             "columnDefs": [
    //                 {
    //                     "targets": [11],
    //                     "data": "coupon_id",
    //                     "render": function (data, type, row, meta) {
    //                         return "<input data-coupon_id=" + data + " type='checkbox'>";
    //                     }
    //                 },
    //                 {
    //                     "targets": [10],
    //                     "data": "coupon_id",
    //                     "render": function (data, type, row, meta) {
    //                         return '<a href="_edit_coupon.php?coupon_id=' + data + '" class="edit_btn mx-1 p-1" data-coupon_id=' + data + '><i class="fas fa-edit"></i></a > <a href="#" class="del-btn mx-1 p-1" data-coupon_id=' + data + '><i class="fas fa-trash-alt"></i></a>';
    //                     }
    //                 },
    //                 // {
    //                 //     "targets": [11],
    //                 //     "data": "coupon_id",
    //                 //     "render": function (data, type, row, meta) {
    //                 //         return '';
    //                 //     }
    //                 // }
    //             ],

    //             data: result.data,
    //             "columns": [
    //                 {"data": "coupon_id"},
    //                 {"data": "coupon_name"},
    //                 {"data": "coupon_code"},
    //                 {
    //                     "data": "created_at",
    //                     "className":"text-truncate"
    //                 },
    //                 {"data": "dis_num"},
    //                 {
    //                     "data": "dis_type",
    //                     "render": function (data) {
    //                         let display = ''
    //                         if(data==1){
    //                             display = "折扣";
    //                         }else if(data==2){
    //                             display= "扣除金額"
    //                         }
    //                         return display;
    //                      }
    //                 },
    //                 {
    //                     "data": "issue_condi",
    //                     "render": function (data) {
    //                         let display = ''
    //                         if(data==1){
    //                             display = "初次登入";
    //                         }else if(data==2){
    //                             display= "會員升等"
    //                         }else if(data ==3){
    //                             display = '訂單累積';
    //                         }
    //                         return display;
    //                      }
    //                 },
    //                 {"data": "coupon_valid"},
    //                 {"data": "coupon_expire"},
    //                 {"data": "user_id"},
    //             ],
    //         })
    //     })
    // }

    // $(document).ready(function () {
    //     $("#fetch_option button").click(function () {
    //         $('#coupon_table').DataTable().destroy();
    //         let sql = $(this).data('sql');
    //         fetch_coupon(sql)
    //     });

    //     //刪除功能
    //     function delete_coupon() {
    //         let coupon_id = $(this).data('coupon_id');
    //         const form = new FormData();
    //         form.append("coupon_id", coupon_id);
    //         if (confirm(`確認是否刪除此筆coupon ID: ${coupon_id}`)) {
    //             fetch('coupon_delete_api.php', {
    //                 method: "POST",
    //                 body: form
    //             }).then(response => {
    //                 return response.json()
    //             }).then(result => {
    //                 console.log(result);
    //                 const info_bar = $("#info_bar");
    //                 info_bar.css("display", "block")
    //                 if (result['success']) {
    //                     info_bar.attr('class', 'alert alert-info').text('刪除成功');
    //                 } else {
    //                     info_bar.attr('class', 'alert alert-danger').text(result.errorMsg);
    //                 }
    //                 setTimeout(function () {
    //                     info_bar.css("display", "none")
    //                 }, 3000)

    //                 $('#coupon_table').DataTable().destroy();
    //             fetch_coupon()
    //             $("#select_all").prop('checked',false)
    //             });

    //         }
    //     }
    //     // $("#coupon_table tbody").on("click", ".del-btn", delete_coupon);
    //     //全選功能
    //     $("#select_all").on('click', function () {
    //         if ($("#select_all").prop('checked')) {
    //             $("tbody :checkbox").each(function () {
    //                 $(this).prop('checked', true)
    //             })
    //         } else {
    //             $("tbody :checkbox").each(function () {
    //                 $(this).prop('checked', false)
    //             })
    //         }
    //     })
    //     //多筆指定
    //     function issue_by_level() {
    //         let form = new FormData();
    //                         let issue_level = $('#issue_level').val();
    //                         form.append('issue_level', issue_level)
    //                         fetch('_issue_by_level_api.php',{
    //                             method:'POST',
    //                             body:form
    //                         })
    //                             .then(response=>response.json())
    //                             .then(data=>{
    //                                 console.log(data);
    //                                 info_bar.css('display', 'block')
    //                                 if(data['success']==true){
    //                                     info_bar.attr('class', 'alert alert-success').html("指派成功");
    //                                 }else {
    //                                     info_bar.attr('class', 'alert alert-danger').html(data['errorMsg']);

    //                                 }
    //                                 setTimeout(function () {
    //                                     info_bar.css('display', 'none')
    //                                 },3000);

    //                                 $('#coupon_table').DataTable().destroy();
    //                                 fetch_coupon()
    //                             })
    //     }
    //     $('#issue_by_level_submit').on('click', issue_by_level)

    //     function group_assign() {
    //     if (confirm('確認指派給此使用者')) {
    //         let form = new FormData();
    //         let assign_couopns = [];
    //         $('#coupon_table tbody :checked').each(function () {
    //             assign_couopns.push($(this).data('coupon_id'))
    //         });
    //         assign_couopns = JSON.stringify(assign_couopns);
    //         form.append('assign_coupons', assign_couopns);


    //         let user_id = $('#assign_by_id').val();
    //         form.append('user_id', user_id);

    //         fetch('_group_assign_api.php', {
    //             method: 'POST',
    //             body: form
    //         })
    //             .then(response => response.json())
    //             .then(data => {
    //                 console.log(data);
    //                 info_bar.css('display', 'block')
    //                 if(data['success']==true){
    //                     info_bar.attr('class', 'alert alert-success').html("指派成功");
    //                 }else {
    //                     info_bar.className = 'alert alert-danger';
    //                     info_bar.attr('class', 'alert alert-danger').html("指派發生錯誤");
    //                 }
    //                 setTimeout(function () {
    //                     info_bar.css('display', 'none')
    //                 },3000);
    //                 $('#coupon_table').DataTable().destroy();
    //                 fetch_coupon()
    //             })
    //     }
    // }
    // $('#group_assign_submit').on('click', group_assign)
    // });
    </script>
    <?php include __DIR__ . './_footer.php'?>