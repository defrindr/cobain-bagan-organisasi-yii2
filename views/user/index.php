<?php

/* @var $this yii\web\View */

$this->title = 'Dashboard';
?>

<style>
    .img-container-kepala {
        overflow: hidden;
        height: auto;
        width: 100%;
    }

    .orgchart .node .content .img-container-overflow {
        height: 100px;
        width: 100px;
        overflow: hidden;
        margin: 10px auto 0;
        display: block;
    }

    .orgchart .node .content .text {
        display: inline-block;
        padding: 10px;
    }

    #chart-container {
        background-color: #fff;
        overflow: auto
    }

    .orgchart {
        background: #fff;
        margin: 0 auto;
        display: block;
    }

    .orgchart .node .title {
        font-size: 1.25rem;
        width: auto;
        height: auto;
    }

    .orgchart .node .content {
        font-size: 1rem;
        height: auto;
    }

    .orgchart .second-menu-icon {
        transition: opacity .5s;
        opacity: 0;
        right: -5px;
        top: -5px;
        z-index: 2;
        position: absolute;
    }

    .orgchart .second-menu-icon::before {
        background-color: rgba(68, 157, 68, 0.5);
    }

    .orgchart .second-menu-icon:hover::before {
        background-color: #449d44;
    }

    .orgchart .node:hover .second-menu-icon {
        opacity: 1;
    }

    .orgchart .node .second-menu {
        display: none;
        position: absolute;
        top: 0;
        right: -70px;
        border-radius: 35px;
        box-shadow: 0 0 10px 1px #999;
        background-color: #fff;
        z-index: 1;
    }

    .orgchart .node .second-menu .avatar {
        width: 60px;
        height: 60px;
        border-radius: 30px;
        float: left;
        margin: 5px;
    }
</style>
<div class="site-index">
    <div id="chart-container" style="width: 100%;height: 750px;"></div>
</div>
<?php richardfan\widget\JSRegister::begin(); ?>
<script>
    var datascource = <?= $struktur ?>;

    var nodeTemplate = function(data) {
        template = `
        <div class="title">${data.name}</div>
        <div class="content">`;

        data.anggota.forEach(function(anggota, index) {
            template += `
            <div class="row">
                <div class="col-md-4">
                    <img src="${anggota.photo_url}" alt="" class="img img-fluid rounded-circle" style="width: 45px">
                </div>
                <div class="col-md-8 text-md-left text-sm-center">
                    <span style='white-space:pre-wrap;display:block'>${anggota.name}</span>
                </div>
            </div>`;

            if (data.anggota.length !== 1 && (data.anggota.length - 1) !== index) {
                template += `<hr>`;
            }
        });

        template += `</div>`;
        return template;
    };

    $('#chart-container').orgchart({
        'data': datascource,
        'visibleLevel': 7,
        'nodeTemplate': nodeTemplate,
        'initCompleted': function() {
            var container = document.querySelector("#chart-container");
            var table = document.querySelector("#chart-container > .orgchart > table");
            let centering = (table.clientWidth / 2) - (container.clientWidth / 2);
            container.scrollLeft = centering;
        }
    });
</script>
<?php richardfan\widget\JSRegister::end(); ?>