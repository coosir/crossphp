<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
    <title>register ui</title>
</head>
<body>
<form id="register_ui" action="" onsubmit="return bgu.submit()" method="post">
    <table>
        <tr><th colspan="2">用户注册</th></tr>
        <tr>
            <td>用户名:</td>
            <td><input type="text" id="inp_name" name="name" id=""/></td>
        </tr>
        <tr>
            <td>密码:</td>
            <td><input type="text" id="inp_pswd" name="password" id=""/></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="提交"/></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
    </table>
</form>
<script type="text/javascript">
    var bgu = {
        submit : function(){
            var n = this.val("inp_name"),p = this.val("inp_pswd");

            if(! n ) {
                this.bor("inp_name", "#f00");
                return false;
            } else {
                this.bor("inp_name", "#fff");
            }

            if(! p) {
                this.bor("inp_pswd", "#f00");
                return false;
            } else {
                this.bor("inp_pswd", "#fff");
            }
            return true;
        },
        val : function(id){
            return document.getElementById(id).value;
        },
        ele : function(id) {
            return document.getElementById(id);
        },
        bor : function(id, color) {
            this.ele(id).style.border = '1px solid '+color;
        }
    }
</script>
</body>
</html>