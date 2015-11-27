<?php defined('WCS_EXPORT_ACCESS') or die(); ?>
<?php
if(!empty($_POST)){
    echo "<pre>"; var_dump($_POST['order_status']); exit;
}
?>

<div class="complete-wrap">
    <h1 id="js-settings">Export Orders</h1>
    <form id="js-afrsettings" method="post">
        <table class="form-table">
            <tbody>
                <tr class="form-field form-required">
                    <th scope="row">
                        <label for="order_status">Order Status </label>
                    </th>
                    <td>
                        <select name="order_status[]" multiple style="height: 200px; width: 200px">
                            <option value="all" selected="selected">All</option>
                            <option value="wc-pending">Pending Payment</option>
                            <option value="wc-processing">Processing</option><option value="wc-on-hold">On Hold</option>
                            <option value="wc-completed">Completed</option><option value="wc-cancelled">Cancelled</option>
                            <option value="wc-refunded">Refunded</option>
                            <option value="wc-failed">Failed</option>
                        </select>
                    </td>
                </tr>

                <tr class="form-field form-required">
                    <th scope="row">
                        <label for="start_date">Start Date </label>
                    </th>
                    <td>
                        <input type="text" value="" class="date_exp" name="start_date" />
                    </td>
                </tr>

                <tr class="form-field form-required">
                    <th scope="row">
                        <label for="end_date">End Date </label>
                    </th>
                    <td>
                        <input type="text" value="" class="date_exp" name="end_date" />
                    </td>
                </tr>

                <tr class="form-field form-required">
                    <th scope="row">
                        <label for="limit_records">Limit Records </label>
                    </th>
                    <td>
                        <input type="number" value="" name="limit_records" />
                    </td>
                </tr>

                <tr class="form-field form-required">
                    <th scope="row">
                        <label for="offset_records">Offset Records </label>
                    </th>
                    <td>
                        <input type="number" value="" name="offset_records" />
                    </td>
                </tr>

            </tbody>
        </table>
        <p class="submit">
            <button class="wps-btn wps-btn-blue" type="submit">
                <span class="text">Update</span>
            </button>
        </p>
    </form>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('.date_exp').datepicker({
            dateFormat : 'yy-mm-dd'
        });
    });
</script>