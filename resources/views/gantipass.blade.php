<style>
h1{
    color:green;
}
.koplak{
    color:red;
    font-weight:bold;
}
</style>
<h1> Form Ganti Password</h1>

<form method="post" action="/api/muser/okgantipas">
    <input name="loginid" type="hidden" value="{{ $loginid }}">
    <table>
        <tr>
            <td>
                <label>Password </label>
            </td>
            <td>
                <input name="password" type="password">
            </td>
        </tr>

        <tr>
            <td>
                <label>Confirm Password </label>
            </td>
            <td>
                <input name="confirmPass" type="password">
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <button type="submit">Submit</button>
            </td>
        </tr>

        
        <tr>
            <td span="2" class="koplak">{{$errs}}</td>
        </tr>
    </table>
</form>