/**
 * Created by zlf on 2018-1-24.
 */

window.onload = function () {
    var list = document.getElementById('list');
    var boxs = list.children;
    var timer;
  
    //格式化日期
    function formateDate(date) {
        var y = date.getFullYear();
        var m = date.getMonth() + 1;
        var d = date.getDate();
        var h = date.getHours();
        var mi = date.getMinutes();
        mi = mi > 9 ? mi : '0' + mi;
        m = m > 9 ? m : '0' + m;
        return y + '-' + m + '-' + d + ' ' + h + ':' + mi;
    }
    

    //删除节点
    function removeNode(node) {
        node.parentNode.removeChild(node);

    }
    /**
     * 功能：点赞功能
     * @param box 每个分享的div容器，box只是一个参数值，它指什么要具体看传进来什么值。（看后面的case值）
     * @param el 点击的元素
     */
    
    function praiseBox(box, el) {
        var txt = el.innerHTML;//获取点击元素的内容
        var praisesTotal = box.getElementsByClassName('praises-total')[0];
        //box.getElementsByClassName('praises-total')取到的是一个数组，通过下标[0]取到第一个元素
        var oldTotal = parseInt(praisesTotal.getAttribute('total'));
        //parseInt() 函数可解析一个字符串，并返回一个整数。此处是将total的值以数值的格式传给oldtotal.
       //total在html页面中定义了
       var newTotal;
        if (txt == '赞') {
           // newTotal = oldTotal + 1;
            //praisesTotal.innerHTML = (newTotal == 1) ? '我觉得很赞' : '我和' + oldTotal + '个人觉得很赞';
            el.innerHTML = '取消赞';
            
        }
        else {
           // newTotal = oldTotal - 1;
            //praisesTotal.innerHTML = (newTotal == 0) ? '' : newTotal + '个人觉得很赞';
            el.innerHTML = '赞';
            
        }
       praisesTotal.setAttribute('total', newTotal);//setAttribute() 方法添加指定的属性，并为其赋指定的值。
       //此处是把newtotal的值赋给total。
       praisesTotal.style.display = (newTotal == 0) ? 'none' : 'block';
       //如果没人点赞，点赞总数的区域就不显示，否则就以块的形式显示。
    }

    /**
     * 功能：发表问题
     * @param box 每个分享的div容器
     * @param el 点击的元素
     */
    //此处的box为box clearfix.
    function question(box, el) {
        var commentList = box.getElementsByClassName('comment-list')[0];
        var textarea = box.getElementsByClassName('comment')[0];
        var commentBox = document.createElement('div');//生成一个div元素
        commentBox.className = 'comment-box clearfix';
        //commentBox.setAttribute('user', 'self');
        commentBox.innerHTML =
                '<div class="comment-content">' +
                //'<p class="comment-text"><span class="user" >我：</span>' + textarea.value + '</p>' +
                '<p class="comment-text"><span class="user" id="UserName" >：'+'</span>' + '<span id="commmentCONT">'+textarea.value+'</span>' + '</p>' +
                '<div class="comment-time">' +formateDate(new Date()) +
                '<div class="comment-op">'+
                '<a href="javascript:" class="comment-praise"  style="margin-right:10px" total="0" my="0" style="">赞</a>' +
                '<a href="javascript:" class="comment-operate" style="margin-right:10px">回复</a>' +
                '<a href="javascript:" class="comment-operate" style="margin-right:10px">删除</a>'+
                '</div>'+
                '<div class="praises-total"  style="display:block;">'+'共有<span id="praisetotal">人觉得很赞</span>'+
                '</div>'+
                '</div>'+
                '</p>' +
                '</div>';
        commentList.appendChild(commentBox);//appendChild() 方法向节点添加最后一个子节点。
        //此处是将commentBox添加到commentlist里面
        textarea.value = '';
        textarea.onblur();
    }


    /**
     * 操作留言
     * @param el 点击的元素
     */
    function operate(el) {
        var commentBox = el.parentNode.parentNode.parentNode;//评论的容器，即取到commentbox
        var box = commentBox.parentNode.parentNode.parentNode;//分享的容器，即整个box
        var txt = el.innerHTML;
        var user = commentBox.getElementsByClassName('user')[0].innerHTML;
        var textarea = box.getElementsByClassName('comment')[0];//获取输入框
        if (txt == '回复') {
            textarea.focus();
            textarea.value = '回复' + user;
            textarea.onkeyup();//计算字数，即计算“回复。。。“几个字的字数
        }
        else {
            removeNode(commentBox.parentNode);//删除整条评论
        }
    }

    //把事件代理到每条分享div容器
    for (var i = 0; i < boxs.length; i++) {

        //点击
        boxs[i].onclick = function (e) {
            e = e || window.event;//为了兼容各种浏览器
            var el = e.srcElement;//将触发事件的对象赋给el
            switch (el.className) {

                //赞分享
                case 'praise':
                    praiseBox(el.parentNode.parentNode.parentNode, el);
                    break;

                //回复按钮蓝
                case 'btn':
                    question(el.parentNode.parentNode.parentNode, el);
                    //el.parentNode.parentNode.parentNode，获取box clearfix的div容器。
                    break;

                //回复按钮灰
                case 'btn btn-off':
                    clearTimeout(timer);//按钮为灰时，清除定时器，即点击灰色按钮，输入框不会缩小
                    break;

                //赞留言
                case 'comment-praise':
                    praiseBox(el.parentNode.parentNode.parentNode, el);
                    break;

                //操作留言
                case 'comment-operate':
                    operate(el);
                    break;
            }
        };

        //评论
        var textArea = boxs[i].getElementsByClassName('comment')[0];//给每个div容器设置输入框

        //评论获取焦点
        textArea.onfocus = function () {
            this.parentNode.className = 'text-box text-box-on';//通过改变classname,改变输入框的样式
            this.value = this.value == '我要提问…' ? '' : this.value;//如果输入框内文字是我要提问…，那就将内容设为空，否则保持原来的值不变
            this.onkeyup();
        };

        //评论失去焦点
        textArea.onblur = function () {
            var me = this;
            var val = me.value;
            if (val == '') {
                timer = setTimeout(function () {
                	//加入定时器
                    me.value = '我要提问…';
                    me.parentNode.className = 'text-box';
                }, 200);//设置失去焦点的200毫秒以后，调用function()函数
            }
        };

        //功能：统计数字
        textArea.onkeyup = function () {
            var val = this.value;
            var len = val.length;
            var els = this.parentNode.children;//即textbox下的所有子节点
            var btn = els[1];//button是第二个子节点
            var word = els[2];//word是第三个子节点
            if (len <=0 || len > 140) { //字数小于0或者大于140时，按钮为灰
                btn.className = 'btn btn-off';
            }
            else {
                btn.className = 'btn';
            }
            word.innerHTML = len + '/140';
        }

    }
};
