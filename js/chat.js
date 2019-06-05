/* 
 * Copyright 2019 Abijah.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */


$(document).ready(function () {
    getUsers();
})
function getUsers(){
    $.ajax({
            type: "POST",
            url: 'server/ajax.php?action=getusers',
            data: '',
            dataType : 'json',
            success: function (resp)
            {
                var response = $.parseJSON(resp);
                if(response.success == "1"){
                    var users = response.users;
                    users.forEach(function(user){
                        console.log(user);
                        $('#users').append("<li id='"+user.id.toString()+"'>@"+user.username.toString()+" - "+user.name.toString()+"</li>");
                    });
                }
            }
        });
}