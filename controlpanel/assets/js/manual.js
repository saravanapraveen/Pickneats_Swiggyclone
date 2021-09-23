window.addEventListener('load', (event) => {
    const queryString = new URLSearchParams(window.location.search)
    let msg = queryString.get('msg')
    let err = queryString.get('err')
    if (msg != null) {
        Snackbar.show({
            text: msg,
            pos: 'bottom-right',
            actionText: 'Success',
            actionTextColor: '#8dbf42',
            duration: 5000
        })
    }
    if (err != null) {
        Snackbar.show({
            text: err,
            pos: 'bottom-right',
            actionText: 'Error',
            actionTextColor: '#000000',
            backgroundColor: '#FF0000',
            duration: 5000
        })
    }
    let current_token = localStorage.getItem('current_token')
    let refresh_token = localStorage.getItem('refresh_token')
    $.ajax({
        type: "POST",
        url: "ajax/checkToken.php",
        data: { 'current_token': current_token,'refresh_token': refresh_token },
        success: function (data) {
            let returnOBJ = JSON.parse(data)

            if(returnOBJ.status){
                if(returnOBJ.current_token){
                    localStorage.setItem('current_token', returnOBJ.current_token)
                }
                let login = returnOBJ.login_id

                $.ajax({
                    type: "POST",
                    url: "ajax/sessionSet.php",
                    data: { 'login': login },
                    success: function (data) {
                        if (window.location.href.charAt(window.location.href.length - 1) == '/') {
                            location.replace('dashboard.php')
                        } else {
                            if (data == 'false') {
                                location.reload()
                            }
                        }
                    }
                })
                controlCheck(login).then(responce => {
                    let output = JSON.parse(responce)
                    if (output.status) {
                        if (output.control == 2) {
                            setInterval(function () {
                                $.ajax({
                                    type: "POST",
                                    url: "ajax/checkOrder.php",
                                    data: { 'login': login },
                                    success: function (data) {
                                        if (data != 'false') {
                                            document.getElementById('notificationContent').innerHTML = data
                                            document.getElementById("audio").play()
                                            document.getElementById('notificationLink').click()
                                        }
                                    }
                                })
                            }, 5000)
                        }
                    }
                })
            } else{
                let thisURl = window.location.href
                let forgotPassword = new RegExp("forgot-password.php").test(thisURl)
                let verification = new RegExp("verification.php").test(thisURl)
                let changePassword = new RegExp("change-password.php").test(thisURl)
                if (thisURl.charAt(thisURl.length - 1) != '/') {
                    if (!forgotPassword && !verification && !changePassword) {
                        location.replace('./')
                    }
                }
            }
        }
    })
})
function sessionSet(login_id,check = null){
    $.ajax({
        type: "POST",
        url: "ajax/sessionSet.php",
        data: { 'login': login_id,'check': check },
        success: function (data) {
            
        }
    })
    return true
}
function loginCheck(e) {
    e.preventDefault()
    let user = document.getElementById('username')
    let pass = document.getElementById('password')

    if (user.value == '') {
        user.style.border = '1px solid red'
        shake(user)
    } else {
        user.style.border = '1px solid #bfc9d4'
        if (pass.value == '') {
            pass.style.border = '1px solid red'
            shake(pass)
        } else {
            pass.style.border = '1px solid #bfc9d4'
            $.ajax({
                type: "POST",
                url: "ajax/loginCheck.php",
                data: { 'username': user.value, 'password': pass.value },
                success: function (data) {
                    let loginObj = JSON.parse(data)
                    if (loginObj.status) {
                        localStorage.setItem('current_token', loginObj.current_token)
                        localStorage.setItem('refresh_token', loginObj.refresh_token)
                        if(sessionSet(loginObj.login_id)){
                            location.replace('dashboard.php');
                        } else{
                            console.log(loginObj.login_id);
                        }
                    } else {
                        if (loginObj.message == 'Incorrect Password!') {
                            document.getElementById('Message').innerHTML = loginObj.message
                            user.style.border = '1px solid #bfc9d4'
                            pass.style.border = '1px solid red'
                            shake(pass)
                        } else {
                            document.getElementById('Message').innerHTML = loginObj.message
                            user.style.border = '1px solid red'
                            shake(user)
                            pass.style.border = '1px solid #bfc9d4'
                        }
                    }
                }
            })
        }
    }
}
function logOut(msg) {
    $.ajax({
        type: "POST",
        url: "ajax/logoutCheck.php",
        data: { 'username': 1, },
        success: function (data) {
            if (data == 'success') {
                localStorage.removeItem('current_token')
                localStorage.removeItem('refresh_token')
                if (msg != undefined) {
                    location.replace('index.php?msg=' + msg)
                } else {
                    location.replace('./')
                }
            }
        }
    })
}
function forgotPassword(e) {
    e.preventDefault()

    let phone = document.getElementById('phone')
    let msg = document.getElementById('Message')

    if (phone.value == '') {
        phone.style.border = '1px solid red'
        shake(phone)
    } else {
        phone.style.border = '1px solid #bfc9d4'
        $.ajax({
            type: "POST",
            url: "ajax/phoneCheck.php",
            data: { 'phone': phone.value },
            success: function (data) {
                let responce = JSON.parse(data)
                if (responce.status == 'success') {
                    if(sessionSet(loginObj.login_id,1)){
                        location.replace('verification.php')
                    }
                } else {
                    msg.innerHTML = responce.message
                }
            }
        })
    }
}
function verifyOTP(e) {
    e.preventDefault()
    let otp = document.getElementById('otp')
    let message = document.getElementById('Message')

    message.innerHTML = ''

    if (otp.value == '') {
        otp.style.border = '1px solid red'
        shake(otp)
    } else {
        otp.style.border = '1px solid #bfc9d4'
        $.ajax({
            type: "POST",
            url: "ajax/verifyOTP.php",
            data: { 'otp': otp.value },
            success: function (data) {
                let responce = JSON.parse(data)

                if (responce.status == 'success') {
                    if(sessionSet(loginObj.login_id)){
                        location.replace('change-password.php')
                    }
                } else {
                    otp.style.border = '1px solid red'
                    shake(otp)
                    message.innerHTML = responce.message
                }
            }
        })
    }
}
function changePassword(e) {
    e.preventDefault()
    let pass = document.getElementById('password')
    let repass = document.getElementById('retype-password')

    let message = document.getElementById('Message')
    message.innerHTML = ''

    if (pass.value == '') {
        pass.style.border = '1px solid red'
        shake(pass)
    } else {
        pass.style.border = '1px solid #bfc9d4'
        if (repass.value == '') {
            repass.style.border = '1px solid red'
            shake(repass)
        } else {
            repass.style.border = '1px solid #bfc9d4'
            if (pass.value == repass.value) {
                $.ajax({
                    type: "POST",
                    url: "ajax/changePassword.php",
                    data: { 'password': pass.value },
                    success: function (data) {
                        let responce = JSON.parse(data)

                        if (responce.status == 'success') {
                            let msg = 'Password changed, Login to continue'
                            logOut(msg)
                        }
                    }
                })
            } else {
                pass.style.border = '1px solid red'
                repass.style.border = '1px solid red'
                shake(pass)
                shake(repass)
                message.innerHTML = 'Password Mismatch'
            }
        }
    }
}
async function controlCheck(login_id) {
    return await $.ajax({
        type: "POST",
        url: "ajax/checkControl.php",
        data: { 'login_id': login_id }
    })
}
function cityCheck(id) {
    let name = document.getElementById('name' + id)
    let latitude = document.getElementById('latitude' + id)
    let longitude = document.getElementById('longitude' + id)

    if (name.value == '') {
        name.style.border = '1px solid red'
        shake(name)
        return false
    } else {
        name.style.border = '1px solid #bfc9d4'
        if (latitude.value == '') {
            latitude.style.border = '1px solid red'
            shake(latitude)
            return false
        } else {
            latitude.style.border = '1px solid #bfc9d4'
            if (longitude.value == '') {
                longitude.style.border = '1px solid red'
                shake(longitude)
                return false
            } else {
                longitude.style.border = '1px solid #bfc9d4'
                return true
            }
        }
    }
}
function areaCheck(e) {
    let name = document.getElementById('name')
    let radius = document.getElementById('radius')
    let controller = document.getElementById('controller_name')
    let phone = document.getElementById('controller_phone')
    let address = document.getElementById('controller_address')
    let username = document.getElementById('username')
    let password = document.getElementById('password')
    let retype = document.getElementById('retype')
    let latitude = document.getElementById('latitude')
    let longitude = document.getElementById('longitude')
    let ErrorMes = document.getElementById('ErrorMes')

    if (name.value == '') {
        name.style.border = '1px solid red'
        shake(name)
        return false
    } else {
        name.style.border = '1px solid #bfc9d4'
        if (controller.value == '') {
            controller.style.border = '1px solid red'
            shake(controller)
            return false
        } else {
            controller.style.border = '1px solid #bfc9d4'
            if (phone.value == '') {
                phone.style.border = '1px solid red'
                shake(phone)
                return false
            } else {
                phone.style.border = '1px solid #bfc9d4'
                if (address.value == '') {
                    address.style.border = '1px solid red'
                    shake(address)
                    return false
                } else {
                    address.style.border = '1px solid #bfc9d4'
                    if (radius.value == '') {
                        radius.style.border = '1px solid red'
                        shake(radius)
                        return false
                    } else {
                        radius.style.border = '1px solid #bfc9d4'
                        if (username.value == '') {
                            username.style.border = '1px solid red'
                            shake(username)
                            return false
                        } else {
                            username.style.border = '1px solid #bfc9d4'
                            if (password.value == '') {
                                password.style.border = '1px solid red'
                                shake(password)
                                return false
                            } else {
                                password.style.border = '1px solid #bfc9d4'
                                if (retype.value == '') {
                                    retype.style.border = '1px solid red'
                                    shake(retype)
                                    return false
                                } else {
                                    retype.style.border = '1px solid #bfc9d4'
                                    if(password.value != retype.value){
                                        password.style.border = '1px solid red'
                                        retype.style.border = '1px solid red'
                                        shake(password)
                                        shake(retype)
                                        return false
                                    } else{
                                        if (latitude.value == '') {
                                            latitude.style.border = '1px solid red'
                                            shake(latitude)
                                            return false
                                        } else {
                                            latitude.style.border = '1px solid #bfc9d4'
                                            if (longitude.value == '') {
                                                longitude.style.border = '1px solid red'
                                                shake(longitude)
                                                return false
                                            } else {
                                                longitude.style.border = '1px solid #bfc9d4'
                                                if (ErrorMes.innerHTML != 'Available') {
                                                    shake(ErrorMes)
                                                    return false
                                                } else{
                                                    return true
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
function cityMap(id) {
    let mapId = 'map' + id
    $.ajax({
        type: "POST",
        url: "ajax/getCityMap.php",
        data: { 'city_id': id },
        success: function (data) {

            let mapData = JSON.parse(data)

            var cities = L.layerGroup()
            var mbUrl = 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw'
            var grayscale = L.tileLayer(mbUrl, { id: 'mapbox/light-v9', tileSize: 512, zoomOffset: -1, })

            var baseLayers = {
                "Grayscale": grayscale
            }

            var overlays = {
                "Cities": cities
            }

            var map = L.map(mapId, {
                center: [mapData.city.city_latitude, mapData.city.city_longitude],
                zoom: 1,
                layers: [grayscale, cities]
            })

            // var map = L.map(mapId).setView([mapData.city.city_latitude,mapData.city.city_longitude], 18)

            L.control.layers(baseLayers, overlays).addTo(map)

            L.marker([mapData.city.city_latitude, mapData.city.city_longitude]).addTo(cities).bindPopup(mapData.city.city_name)
        }
    })
}
function serviceStatus(service_id, city_id) {
    $.ajax({
        type: "POST",
        url: "ajax/serviceStatus.php",
        data: { 'service_id': service_id, 'city_id': city_id },
        success: function (data) {
            let responce = JSON.parse(data)
            if (responce.status == 'success') {
                Snackbar.show({
                    text: responce.message,
                    pos: 'bottom-right',
                    actionText: 'Success',
                    actionTextColor: '#A1D433',
                    duration: 5000
                })
                return true
            } else {
                Snackbar.show({
                    text: responce.message,
                    pos: 'bottom-right',
                    actionText: 'Error',
                    actionTextColor: '#8dbf42',
                    duration: 5000
                })
                if (document.getElementById('status' + service_id).checked) {
                    document.getElementById('status' + service_id).checked = false
                } else {
                    document.getElementById('status' + service_id).checked = true
                }
            }
        }
    })
}
function checkUsername(user_name, login_id) {
    let ErrorTag
    if (login_id == undefined) {
        ErrorTag = document.getElementById('ErrorMes')
    } else {
        ErrorTag = document.getElementById('ErrorMes' + login_id)
    }

    if (user_name != '') {
        $.ajax({
            type: "POST",
            url: "ajax/checkAdmin.php",
            data: { 'user_name': user_name, 'login_id': login_id },
            success: function (data) {
                let responce = JSON.parse(data)
                if (responce.status == true) {
                    ErrorTag.innerHTML = "Already Exist"
                    ErrorTag.style.color = "red"
                } else {
                    ErrorTag.innerHTML = "Available"
                    ErrorTag.style.color = "green"
                }

            }
        })
    } else {
        ErrorTag.innerHTML = ""
    }
}
function checkPhone(user_phone, login_id) {
    let ErrorTag
    if (login_id == undefined) {
        ErrorTag = document.getElementById('PhoneErrorMes')
    } else {
        ErrorTag = document.getElementById('PhoneErrorMes' + login_id)
    }
    if (user_phone != '') {
        $.ajax({
            type: "POST",
            url: "ajax/checkPhone.php",
            data: { 'user_phone': user_phone, 'login_id': login_id },
            success: function (data) {
                let responce = JSON.parse(data)
                if (responce.status == true) {
                    ErrorTag.innerHTML = "Already Exist"
                    ErrorTag.style.color = "red"
                } else {
                    ErrorTag.innerHTML = "Available"
                    ErrorTag.style.color = "green"
                }
            }
        })
    } else {
        ErrorTag.innerHTML = ""
    }
}
function addOwner(login_id) {
    let name = document.getElementById('name' + login_id)
    let phone = document.getElementById('phone' + login_id)
    let user = document.getElementById('username' + login_id)
    let userError = document.getElementById('ErrorMes' + login_id)
    let phoneError = document.getElementById('PhoneErrorMes' + login_id)
    let pass = document.getElementById('password')
    let retypepassword = document.getElementById('retypepassword')

    if (name.value == '') {
        name.style.border = '1px solid red'
        shake(name)
        return false
    } else {
        name.style.border = '1px solid #bfc9d4'
        if (user.value == '') {
            user.style.border = '1px solid red'
            shake(user)
            return false
        } else {
            user.style.border = '1px solid #bfc9d4'
            if (userError.innerHTML != 'Available') {
                user.style.border = '1px solid red'
                shake(user)
                return false
            } else {
                user.style.border = '1px solid #bfc9d4'
                if (phone.value == '') {
                    phone.style.border = '1px solid red'
                    shake(phone)
                    return false
                } else {
                    phone.style.border = '1px solid #bfc9d4'
                    if (phoneError.innerHTML != 'Available') {
                        phone.style.border = '1px solid red'
                        shake(phone)
                        return false
                    } else {
                        if (login_id == undefined) {
                            if (pass.value == '') {
                                pass.style.border = '1px solid red'
                                shake(pass)
                                return false
                            } else {
                                pass.style.border = '1px solid #bfc9d4'
                                if (retypepassword.value == '') {
                                    retypepassword.style.border = '1px solid red'
                                    shake(retypepassword)
                                    return false
                                } else {
                                    retypepassword.style.border = '1px solid #bfc9d4'
                                    if (pass.value != retypepassword.value) {
                                        pass.style.border = '1px solid red'
                                        retypepassword.style.border = '1px solid red'
                                        shake(pass)
                                        shake(retypepassword)
                                        return false
                                    }
                                }
                            }
                        } else {
                            return true
                        }
                    }
                }
            }
        }
    }
}
function changePasswordLogin(login_id) {
    let pass = document.getElementById('password' + login_id)
    let retypepassword = document.getElementById('retypePassword' + login_id)

    if (pass.value == '') {
        pass.style.border = '1px solid red'
        shake(pass)
        return false
    } else {
        pass.style.border = '1px solid #bfc9d4'
        if (retypepassword.value == '') {
            retypepassword.style.border = '1px solid red'
            shake(retypepassword)
            return false
        } else {
            retypepassword.style.border = '1px solid #bfc9d4'
            if (pass.value != retypepassword.value) {
                pass.style.border = '1px solid red'
                retypepassword.style.border = '1px solid red'
                shake(pass)
                shake(retypepassword)
                return false
            }
        }
    }
}
function checkService(service_name, service_id) {
    let ErrorTag
    if (service_id == undefined) {
        ErrorTag = document.getElementById('errorMsg')
    } else {
        ErrorTag = document.getElementById('editErrorMsg' + service_id)
    }
    $.ajax({
        type: "POST",
        url: "ajax/checkService.php",
        data: { 'service_name': service_name, 'service_id': service_id },
        success: function (data) {
            let responce = JSON.parse(data)

            if (responce.status == true) {
                ErrorTag.innerHTML = "Already Exist"
                ErrorTag.style.color = "red"
            } else {
                ErrorTag.innerHTML = "Available"
                ErrorTag.style.color = "green"
            }

        }
    })
}
function serviceCheck(event, service_id) {
    let name
    let errorMsg

    if (service_id == undefined) {
        name = document.getElementById('name')
        errorMsg = document.getElementById('errorMsg')
    } else {
        name = document.getElementById('name' + service_id)
        errorMsg = document.getElementById('editErrorMsg' + service_id)
    }

    if (name.value == '') {
        name.style.border = '1px solid red'
        shake(name)
        event.preventDefault()
    } else {
        name.style.border = '1px solid #bfc9d4'
        if (errorMsg.innerHTML == 'Already Exist') {
            name.style.border = '1px solid red'
            shake(name)
            event.preventDefault()
        } else {
            name.style.border = '1px solid #bfc9d4'
            return true
        }
    }
}
function adminValidation() {
    let admin_name = document.getElementById('name')
    let user_name = document.getElementById('user_name')
    let phone = document.getElementById('phone')
    let city = document.getElementById('city')
    let password = document.getElementById('password')
    let re_password = document.getElementById('re_password')
    let address = document.getElementById('address')
    let PasswordError = document.getElementById('PasswordError')
    let ErrorMes = document.getElementById('ErrorMes')
    let PhoneErrorMes = document.getElementById('PhoneErrorMes')

    if (admin_name.value == "") {
        admin_name.style.border = '1px solid red'
        shake(admin_name)
        return false
    } else {
        admin_name.style.border = '1px solid #bfc9d4'
        if (user_name.value == "") {
            user_name.style.border = '1px solid red'
            shake(user_name)
            return false
        } else {
            user_name.style.border = '1px solid #bfc9d4'
            if (ErrorMes.innerHTML == "Already Exist") {
                user_name.style.border = '1px solid red'
                shake(user_name)
                return false
            } else {
                user_name.style.border = '1px solid #bfc9d4'
                if (phone.value == "") {
                    phone.style.border = '1px solid red'
                    shake(phone)
                    return false
                } else {
                    phone.style.border = '1px solid #bfc9d4'
                    if (PhoneErrorMes.innerHTML == "Already Exist") {
                        phone.style.border = '1px solid red'
                        shake(phone)
                        return false
                    } else {
                        phone.style.border = '1px solid #bfc9d4'
                        if (city.value == "") {
                            city.style.border = '1px solid red'
                            shake(city)
                            return false
                        } else {
                            city.style.border = '1px solid #bfc9d4'
                            if (password.value == "") {
                                password.style.border = '1px solid red'
                                shake(password)
                                return false
                            } else {
                                password.style.border = '1px solid #bfc9d4'
                                if (re_password.value == "") {
                                    re_password.style.border = '1px solid red'
                                    shake(re_password)
                                    return false
                                } else {
                                    re_password.style.border = '1px solid #bfc9d4'
                                    if (address.value == "") {
                                        address.style.border = '1px solid red'
                                        shake(address)
                                        return false
                                    } else {
                                        address.style.border = '1px solid #bfc9d4'

                                        if (password.value != re_password.value) {
                                            password.style.border = '1px solid red'
                                            re_password.style.border = '1px solid red'
                                            shake(password)
                                            shake(re_password)
                                            PasswordError.innerHTML = "Password Mismatch"
                                            PasswordError.style.color = "red"
                                            return false
                                        } else {
                                            password.style.border = '1px solid #bfc9d4'
                                            re_password.style.border = '1px solid #bfc9d4'
                                            PasswordError.innerHTML = ""
                                            return true
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
function adminUpdateValidation(login_id) {
    let admin_name = document.getElementById('name' + login_id)
    let user_name = document.getElementById('user_name' + login_id)
    let phone = document.getElementById('phone' + login_id)
    let city = document.getElementById('city' + login_id)
    let address = document.getElementById('address' + login_id)

    if (admin_name.value == "") {
        admin_name.style.border = '1px solid red'
        shake(admin_name)
        return false
    } else {
        admin_name.style.border = '1px solid #bfc9d4'
        if (user_name.value == "") {
            user_name.style.border = '1px solid red'
            shake(user_name)
            return false
        } else {
            user_name.style.border = '1px solid #bfc9d4'
            if (phone.value == "") {
                phone.style.border = '1px solid red'
                shake(phone)
                return false
            } else {
                phone.style.border = '1px solid #bfc9d4'
                if (city.value == "") {
                    city.style.border = '1px solid red'
                    shake(city)
                    return false
                } else {
                    city.style.border = '1px solid #bfc9d4'
                    if (address.value == "") {
                        address.style.border = '1px solid red'
                        shake(address)
                        return false
                    } else {
                        address.style.border = '1px solid #bfc9d4'
                        return true
                    }
                }
            }
        }
    }
}
function checkadminpassword(login_id) {
    let password = document.getElementById('password' + login_id)
    let re_password = document.getElementById('re_password' + login_id)
    let ErroMsg = document.getElementById('ErroMsg' + login_id)

    if (password.value == "") {
        password.style.border = '1px solid red'
        shake(password)
        return false
    } else {
        password.style.border = '1px solid #bfc9d4'
        if (re_password.value == "") {
            re_password.style.border = '1px solid red'
            shake(re_password)
            return false
        } else {
            if (password.value != re_password.value) {
                ErroMsg.innerHTML = "Password Mismatch"
                return false
            } else {
                $.ajax({
                    type: "POST",
                    url: "ajax/changePassword.php",
                    data: { 'password': password.value, 'login_id': login_id },
                    success: function (data) {
                        console.log(data)
                        let responce = JSON.parse(data)
                        if (responce.status == 'success') {
                            location.replace('admin.php?msg=Password changed!')
                        }
                    }
                })
            }
        }
    }
}
function shopCheck() {
    let user = document.getElementById('username')
    let pass = document.getElementById('password')
    let retypepassword = document.getElementById('retypepassword')
    let Error = document.getElementById('Error')

    Error.innerHTML = ''

    if (ErrorMes.innerHTML == "Already Exist") {
        user.style.border = '1px solid red'
        shake(user)
        Error.innerHTML = 'Username exist!'
        return false
    } else {
        user.style.border = '1px solid #bfc9d4'
        if (pass.value != retypepassword.value) {
            pass.style.border = '1px solid red'
            retypepassword.style.border = '1px solid red'
            shake(pass)
            shake(retypepassword)
            Error.innerHTML = '*Password mismatch'
            return false
        } else {
            pass.style.border = '1px solid #bfc9d4'
            retypepassword.style.border = '1px solid #bfc9d4'
        }
    }
}
function categoryValidate(value, login_id, category_id) {
    if (category_id == undefined) {
        category_id = ''
    }
    let ErrorMes = document.getElementById('ErrorMes' + category_id)
    ErrorMes.innerHTML = ''
    if (value != '') {
        $.ajax({
            type: "POST",
            url: "ajax/categoryCheck.php",
            data: { 'category_name': value, 'login_id': login_id, 'category_id': category_id },
            success: function (data) {
                let responce = JSON.parse(data)
                if (responce.status == false) {
                    ErrorMes.innerHTML = 'Category exist!'
                } else {
                    ErrorMes.innerHTML = 'Available'
                }
            }
        })
    }
}
function categoryCheck(category_id) {
    if (category_id == undefined) {
        category_id = ''
    }
    let name = document.getElementById('name' + category_id)
    let image = document.getElementById('image' + category_id)
    let ErrorMes = document.getElementById('ErrorMes' + category_id)

    if (name.value == "") {
        name.style.border = '1px solid red'
        shake(name)
        return false
    } else {
        name.style.border = '1px solid #bfc9d4'
        if (ErrorMes.innerHTML == "Category exist!") {
            name.style.border = '1px solid red'
            shake(name)
            return false
        } else {
            name.style.border = '1px solid #bfc9d4'
            if (image.value == "") {
                image.style.border = '1px solid red'
                shake(image)
                return false
            }
        }
    }
}
function categoryStatus(category_id) {
    $.ajax({
        type: "POST",
        url: "ajax/categoryStatus.php",
        data: { 'category_id': category_id },
        success: function (data) {
            let responce = JSON.parse(data)
            if (responce.status == true) {
                Snackbar.show({
                    text: responce.message,
                    pos: 'bottom-right',
                    actionText: 'Success',
                    actionTextColor: '#A1D433',
                    duration: 5000
                })
                return true
            } else {
                Snackbar.show({
                    text: responce.message,
                    pos: 'bottom-right',
                    actionText: 'Error',
                    actionTextColor: '#8dbf42',
                    duration: 5000
                })
                if (document.getElementById('status' + category_id).checked) {
                    document.getElementById('status' + category_id).checked = false
                } else {
                    document.getElementById('status' + category_id).checked = true
                }
            }
        }
    })
}
function arrangeCategory(category_id, value) {
    $.ajax({
        type: "POST",
        url: "ajax/arrangeCategory.php",
        data: { 'category_id': category_id, 'value': value },
        success: function (data) {
            return data
        }
    })
}

function checkTiming() {
    let timing_name = document.getElementById('name')

    if (timing_name.value == "") {
        timing_name.style.border = '1px solid red'
        shake(timing_name)
        return false
    } else {
        timing_name.style.border = '1px solid #bfc9d4'
        return true
    }
}

function checkAddon() {
    let name = document.getElementById('name')
    let price = document.getElementById('price')

    if (name.value == "") {
        name.style.border = '1px solid red'
        shake(name)
        return false
    } else {
        name.style.border = '1px solid #bfc9d4'
        if (price.value == "") {
            price.style.border = '1px solid red'
            shake(price)
            return false
        } else {
            price.style.border = '1px solid #bfc9d4'
            return true
        }
    }
}
function editcheckAddon(id) {
    let name = document.getElementById('name' + id)
    let price = document.getElementById('price' + id)

    if (name.value == "") {
        name.style.border = '1px solid red'
        shake(name)
        return false
    } else {
        name.style.border = '1px solid #bfc9d4'
        if (price.value == "") {
            price.style.border = '1px solid red'
            shake(price)
            return false
        } else {
            price.style.border = '1px solid #bfc9d4'
            return true
        }
    }
}

function addonStatus(addon_id) {
    $.ajax({
        type: "POST",
        url: "ajax/addonStatus.php",
        data: { 'addon_id': addon_id },
        success: function (data) {
            let responce = JSON.parse(data)
            if (responce.status == 'success') {
                Snackbar.show({
                    text: responce.message,
                    pos: 'bottom-right',
                    actionText: 'Success',
                    actionTextColor: '#A1D433',
                    duration: 5000
                })
                return true
            } else {
                Snackbar.show({
                    text: responce.message,
                    pos: 'bottom-right',
                    actionText: 'Error',
                    actionTextColor: '#8dbf42',
                    duration: 5000
                })
                if (document.getElementById('status' + addon_id).checked) {
                    document.getElementById('status' + addon_id).checked = false
                } else {
                    document.getElementById('status' + addon_id).checked = true
                }
            }
        }
    })
}
function cityStatus(city_id) {
    $.ajax({
        type: "POST",
        url: "ajax/cityStatus.php",
        data: { 'city_id': city_id },
        success: function (data) {
            let responce = JSON.parse(data)
            if (responce.status == 'success') {
                Snackbar.show({
                    text: responce.message,
                    pos: 'bottom-right',
                    actionText: 'Success',
                    actionTextColor: '#A1D433',
                    duration: 5000
                })
                return true
            } else {
                Snackbar.show({
                    text: responce.message,
                    pos: 'bottom-right',
                    actionText: 'Error',
                    actionTextColor: '#8dbf42',
                    duration: 5000
                })
                if (document.getElementById('status' + city_id).checked) {
                    document.getElementById('status' + city_id).checked = false
                } else {
                    document.getElementById('status' + city_id).checked = true
                }
            }
        }
    })
}
function productStatus(product_id) {
    $.ajax({
        type: "POST",
        url: "ajax/productStatus.php",
        data: { 'product_id': product_id },
        success: function (data) {
            let responce = JSON.parse(data)
            if (responce.status == 'success') {
                Snackbar.show({
                    text: responce.message,
                    pos: 'bottom-right',
                    actionText: 'Success',
                    actionTextColor: '#A1D433',
                    duration: 5000
                })
                return true
            } else {
                Snackbar.show({
                    text: responce.message,
                    pos: 'bottom-right',
                    actionText: 'Error',
                    actionTextColor: '#8dbf42',
                    duration: 5000
                })
                if (document.getElementById('status' + product_id).checked) {
                    document.getElementById('status' + product_id).checked = false
                } else {
                    document.getElementById('status' + product_id).checked = true
                }
            }
        }
    })
}

function checkCombo(combo_name) {
    $.ajax({
        type: "POST",
        url: "ajax/comboCheck.php",
        data: { 'combo_name': combo_name },
        success: function (data) {
            let responce = JSON.parse(data)
            if (responce.status == true) {
                document.getElementById('errorMsg').innerHTML = "Combo Name Already Exist"
                document.getElementById('errorMsg').style.color = "red"
            } else {
                document.getElementById('errorMsg').innerHTML = "Combo Name Available"
                document.getElementById('errorMsg').style.color = "green"
            }
        }
    })
}
function editcheckCombo(combo_name, combo_id) {
    $.ajax({
        type: "POST",
        url: "ajax/comboCheck.php",
        data: { 'combo_name': combo_name, 'combo_id': combo_id },
        success: function (data) {
            let responce = JSON.parse(data)
            if (responce.status == true) {
                document.getElementById('editErrorMsg' + combo_id).innerHTML = "Combo Name Already Exist"
                document.getElementById('editErrorMsg' + combo_id).style.color = "red"
            } else {
                document.getElementById('editErrorMsg' + combo_id).innerHTML = "Combo Name Available"
                document.getElementById('editErrorMsg' + combo_id).style.color = "green"
            }
        }
    })
}

function comboCheck() {
    let combo_name = document.getElementById('combo_name')
    let combo_image = document.getElementById('combo_image')
    let combo_product = document.getElementById('combo_product')
    let combo_price = document.getElementById('combo_price')

    if (combo_name.value == "") {
        combo_name.style.border = '1px solid red'
        shake(combo_name)
        return false
    } else {
        combo_name.style.border = '1px solid #bfc9d4'
        if (combo_image.value == "") {
            combo_image.style.border = '1px solid red'
            shake(combo_image)
            return false
        } else {
            combo_image.style.border = '1px solid #bfc9d4'
            // if(combo_product.value ==""){
            //     combo_product.style.border = '1px solid red'
            //     return false
            // }else{
            //     combo_product.style.border = '1px solid #bfc9d4'
            if (combo_price.value == "") {
                combo_price.style.border = '1px solid red'
                shake(combo_price)
                return false
            } else {
                combo_price.style.border = '1px solid #bfc9d4'
                return true
            }
            // }

        }
    }
}
function editcomboCheck(combo_id) {
    let combo_name = document.getElementById('combo_name' + combo_id)
    let combo_image = document.getElementById('combo_image' + combo_id)
    let combo_price = document.getElementById('combo_price' + combo_id)
    let city = document.getElementById('city' + combo_id)

    if (combo_name.value == "") {
        combo_name.style.border = '1px solid red'
        shake(combo_name)
        return false
    } else {
        combo_name.style.border = '1px solid #bfc9d4'
        if (combo_image.value == "") {
            combo_image.style.border = '1px solid red'
            shake(combo_image)
            return false
        } else {
            combo_image.style.border = '1px solid #bfc9d4'
            // if(combo_product.value ==""){
            //     combo_product.style.border = '1px solid red'
            //     return false
            // }else{
            //     combo_product.style.border = '1px solid #bfc9d4'
            if (combo_price.value == "") {
                combo_price.style.border = '1px solid red'
                shake(combo_price)
                return false
            } else {
                combo_price.style.border = '1px solid #bfc9d4'
                if (city.value == "") {
                    city.style.border = '1px solid red'
                    shake(city)
                    return false
                } else {
                    city.style.border = '1px solid #bfc9d4'
                    return true
                }
            }
            // }

        }
    }
}
function comboOffer() {
    let coupon_code = document.getElementById('coupon_code')
    let minimum_order = document.getElementById('minimum_order')
    let city = document.getElementById('city')


    if (coupon_code.value == "") {
        coupon_code.style.border = '1px solid red'
        shake(coupon_code)
        return false
    } else {
        coupon_code.style.border = '1px solid #bfc9d4'
        if (minimum_order.value == "") {
            minimum_order.style.border = '1px solid red'
            shake(coupon_code)
            return false
        } else {
            minimum_order.style.border = '1px solid #bfc9d4'
            if (city.value == "") {
                city.style.border = '1px solid red'
                shake(city)
                return false
            } else {
                city.style.border = '1px solid #bfc9d4'
                return true
            }
        }
    }
}

function checkOfferName(value) {
    $.ajax({
        type: "POST",
        url: "ajax/CheckOffer.php",
        data: { 'offer_name': value },
        success: function (data) {
            let responce = JSON.parse(data)
            if (responce.status == true) {
                document.getElementById('errorMsg').innerHTML = "Coupon Code Already Exist"
                document.getElementById('errorMsg').style.color = "red"
            } else {
                document.getElementById('errorMsg').innerHTML = "Coupon Code Available"
                document.getElementById('errorMsg').style.color = "green"
            }
        }
    })
}
function editcheckOfferName(value, offer_id) {
    $.ajax({
        type: "POST",
        url: "ajax/CheckOffer.php",
        data: { 'offer_name': value, 'offer_id': offer_id },
        success: function (data) {
            let responce = JSON.parse(data)
            if (responce.status == true) {
                document.getElementById('editErrorMsg' + offer_id).innerHTML = "Coupon Code Already Exist"
                document.getElementById('editErrorMsg' + offer_id).style.color = "red"
            } else {
                document.getElementById('editErrorMsg' + offer_id).innerHTML = "Coupon Code Available"
                document.getElementById('editErrorMsg' + offer_id).style.color = "green"
            }
        }
    })
}

function setFlat() {
    document.getElementById('falt_amount').value = 0
}
function setPercentage() {
    document.getElementById('percentage').value = "0"
    document.getElementById('maximum_discount').value = "0"
}

function controlsCheck() {
    let maximum_distance = document.getElementById('maximum_distance')
    let pick_delivery_charge = document.getElementById('pick_delivery_charge')
    let first_mile_charge = document.getElementById('first_mile_charge')
    let last_mile_charge = document.getElementById('last_mile_charge')

    if (maximum_distance.value == "") {
        maximum_distance.style.border = '1px solid red'
        shake(maximum_distance)
        return false
    } else {
        maximum_distance.style.border = '1px solid #bfc9d4'
        if (pick_delivery_charge.value == "") {
            pick_delivery_charge.style.border = '1px solid red'
            shake(pick_delivery_charge)
            return false
        } else {
            pick_delivery_charge.style.border = '1px solid #bfc9d4'
            if (pick_delivery_charge.value == "") {
                pick_delivery_charge.style.border = '1px solid red'
                shake(pick_delivery_charge)
                return false
            } else {
                pick_delivery_charge.style.border = '1px solid #bfc9d4'
                if (first_mile_charge.value == "") {
                    first_mile_charge.style.border = '1px solid red'
                    shake(first_mile_charge)
                    return false
                } else {
                    first_mile_charge.style.border = '1px solid #bfc9d4'
                    if (last_mile_charge.value == "") {
                        last_mile_charge.style.border = '1px solid red'
                        shake(last_mile_charge)
                        return false
                    } else {
                        last_mile_charge.style.border = '1px solid #bfc9d4'
                        return true
                    }
                }
            }
        }
    }
}

function getShops(city) {
    $.ajax({
        type: "POST",
        url: "ajax/getCity.php",
        data: 'city=' + city,
        beforeSend: function () {
            $("#shops").addClass("loader")
        },
        success: function (data) {
            $("#shops").html(data)
            $("#shops").removeClass("loader")
        }
    })
}

function editgetShops(city, id) {
    $.ajax({
        type: "POST",
        url: "ajax/getCity.php",
        data: 'city=' + city,
        beforeSend: function () {
            $("#shops" + id).addClass("loader")
        },
        success: function (data) {
            $("#shops" + id).html(data)
            $("#shops" + id).removeClass("loader")
        }
    })
}

function unitCheck() {
    let name = document.getElementById('name')
    if (name.value == "") {
        name.style.border = '1px solid red'
        shake(name)
        return false
    } else {
        name.style.border = '1px solid #bfc9d4'
        return true
    }
}
function editunitCheck(unit_id) {
    let name = document.getElementById('name' + unit_id)
    if (name.value == "") {
        name.style.border = '1px solid red'
        shake(name)
        return false
    } else {
        name.style.border = '1px solid #bfc9d4'
        return true
    }
}

function unitValidate(val) {
    $.ajax({
        type: "POST",
        url: "ajax/Checkunit.php",
        data: { 'unit_name': val },
        success: function (data) {
            let responce = JSON.parse(data)
            if (responce.status == true) {
                document.getElementById('ErrorMes').innerHTML = "Unit Name Already Exist"
                document.getElementById('ErrorMes').style.color = "red"
            } else {
                document.getElementById('ErrorMes').innerHTML = "Unit Name Available"
                document.getElementById('ErrorMes').style.color = "green"
            }
        }
    })
}
function editUnitValidate(val, id) {
    $.ajax({
        type: "POST",
        url: "ajax/Checkunit.php",
        data: { 'unit_name': val, 'unit_id': id },
        success: function (data) {
            let responce = JSON.parse(data)
            if (responce.status == true) {
                document.getElementById('ErrorMes' + id).innerHTML = "Unit Name Already Exist"
                document.getElementById('ErrorMes' + id).style.color = "red"
            } else {
                document.getElementById('ErrorMes' + id).innerHTML = "Unit Name Available"
                document.getElementById('ErrorMes' + id).style.color = "green"
            }
        }
    })
}
var shake = function (element, magnitude = 16) {
    var shakingElements = []
    var tiltAngle = 1
    var counter = 1
    var numberOfShakes = 15
    var startX = 0,startY = 0,startAngle = 0
    var magnitudeUnit = magnitude / numberOfShakes
    var randomInt = (min, max) => {
        return Math.floor(Math.random() * (max - min + 1)) + min
    }
    if (shakingElements.indexOf(element) === -1) {
        shakingElements.push(element)
        upAndDownShake()
    }
    function upAndDownShake() {
        if (counter < numberOfShakes) {
            element.style.transform = 'translate(' + startX + 'px, ' + startY + 'px)'
            magnitude -= magnitudeUnit
            var randomX = randomInt(-magnitude, magnitude)
            var randomY = randomInt(-magnitude, magnitude)
            element.style.transform = 'translate(' + randomX + 'px, ' + randomY + 'px)'
            counter += 1
            requestAnimationFrame(upAndDownShake)
        }
        if (counter >= numberOfShakes) {
            element.style.transform = 'translate(' + startX + ', ' + startY + ')'
            shakingElements.splice(shakingElements.indexOf(element), 1)
        }
    }
}