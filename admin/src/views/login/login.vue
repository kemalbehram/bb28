<template>
    <div class="login">
        <div class="login-con">
            <Card :bordered="false" icon="log-in" title="英利28管理系统">
                <div class="form-con">
                    <login-form></login-form>
                </div>
            </Card>
        </div>
    </div>
</template>

<script>
import loginForm from './login-form'
import registerForm from './register-form'

export default {
    components: {
        loginForm,
        registerForm,
    },
}
</script>

<style lang="less">
.login {
    width: 100%;
    height: 100%;
    background-image: url('../../assets/images/login-bg.jpg');
    background-size: cover;
    background-position: center;
    position: relative;
    &-con {
        position: absolute;
        right: 160px;
        top: 50%;
        transform: translateY(-60%);
        width: 300px;
        &-header {
            font-size: 16px;
            font-weight: 300;
            text-align: center;
            padding: 30px 0;
        }
        .form-con {
            padding: 6px 0;
        }
        .login-tip {
            margin-top: 10px;
            font-size: 10px;
            text-align: center;
            color: #c3c3c3;
        }
    }
}

.or-wrap {
    margin: 10px 0;
    text-align: center;
}
</style>
