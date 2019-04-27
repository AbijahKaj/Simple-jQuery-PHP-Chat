/* 
 * Copyright 2019 Abijah Kajabika.
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
    $('#msgform').submit(function (e) {
        e.preventDefault();
        e.stopPropagation();
        $.ajax({
            type: "POST",
            url: 'server/chat.php',
            data: $(this).serialize(),
            success: function (response)
            {
                var jsonData = JSON.parse(response);
                if (jsonData.success == "1")
                {
                    popUpMSG(jsonData);
                } else
                {
                    alert(jsonData.msg);
                }
            }
        });
        $('.card-body').animate({scrollTop: $('.card-body').height() + $('.card-body').scrollTop()}, 800);
    });
});
function popUpMSG(data) {
    var feed = $('.card-body');
    feed.append('<div class="message-block-receive"><div class="user">Me</div><div class="message">' + data.msg + '</div><div class="time">' + data.date + '</div></div>');
}