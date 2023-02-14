<?php
class Postorderdata_table extends MX_Controller
{
    public function column_list()
    {
        $column_structure = array(
            0 => 'Field:id, Type:int(11), Null:NO, Key:PRI, Default:, Extra:auto_increment',
            1 => 'Field:created_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            2 => 'Field:created_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            3 => 'Field:modified_by, Type:int(11), Null:NO, Key:, Default:, Extra:',
            4 => 'Field:modified_date, Type:datetime, Null:NO, Key:, Default:, Extra:',
            5 => 'Field:status, Type:tinyint(1), Null:NO, Key:, Default:1, Extra:',
            6 => 'Field:razorpay_orderid, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            7 => 'Field:razorpay_paymentid, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            8 => 'Field:razorpay_signature, Type:varchar(255), Null:NO, Key:, Default:, Extra:',
            9 => 'Field:amount, Type:decimal(20,2), Null:NO, Key:, Default:, Extra:',
            10 => 'Field:currency, Type:varchar(11), Null:NO, Key:, Default:, Extra:',
            11 => 'Field:is_varified, Type:tinyint(1), Null:NO, Key:, Default:2, Extra:',
            12 => 'Field:unvarified_error, Type:text, Null:NO, Key:, Default:, Extra:',
        );
        return $column_structure;
    }
}
