<?php

namespace app\controllers;

//use app\components\NodeLogger;

use app\models\Role;
use app\models\User;
use Yii;
use yii\web\Controller;

class SiteController extends Controller
{

    public function actionIndex()
    {
        $ketua = Role::find()->where([
            'and',
            ["is not", "urutan", null],
            ["is", "parent_id", null],
        ])
            ->select(['id', 'name'])
            ->andWhere(['id_master_karang_taruna' => 4168716])
            ->asArray()
            ->one();

        $urutan_jabatan = Role::find()->andWhere(['id_master_karang_taruna' => 4168716])->orderBy(['urutan' => SORT_ASC])->asArray()->all();

        $struktur = $this->checkRelation($ketua, $urutan_jabatan, $karang_taruna_id = 4168716);
        $ketua["anggota"] = $this->anggota($ketua['id'], $karang_taruna_id);
        $ketua["children"] = $struktur;
        $struktur = $ketua;
        $struktur = json_encode($struktur, JSON_PRETTY_PRINT);
        return $this->render('index', compact('struktur'));
    }
    /**
     * checkRelation
     * @params $data -> data parent
     * @params $array -> kelompok data
     * Berfungsi untuk menampilkan nested array dari struktur organisasi
     */
    private function checkRelation($data, $array, $karang_taruna_id)
    {
        $ret = [];
        foreach ($array as $arr) {
            if (($data['id'] == $arr['parent_id'] && ($arr['id'] != $arr['parent_id']))) {

                array_push($ret, [
                    "name" => $arr['name'],
                    "anggota" => $this->anggota($arr['id'], $karang_taruna_id),
                    "children" => $this->checkRelation($arr, $array, $karang_taruna_id),
                ]);
            }
        }

        return $ret;
    }

    private function anggota($role_id, $karang_taruna_id)
    {
        $anggota = User::find()->where(['role_id' => $role_id, 'id_master_karang_taruna' => $karang_taruna_id])->select(['name', 'concat("' . Yii::$app->homeUrl . '/uploads/", photo_url) as photo_url'])->asArray()->all();
        return $anggota;
    }
}
