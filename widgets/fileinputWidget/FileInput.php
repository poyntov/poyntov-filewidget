<?php

namespace app\widgets\fileinputWidget;

use app\models\File;
use app\models\Meta;
use Yii;
use yii\base\DynamicModel;
use yii\base\Widget;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class FileInput extends Widget
{
    /**
     * @var File
     */
    public $model;
    public $attribute;
    public $metaAttribute;
    public $extensions = [];
    public $maxSizeOne;
    public $maxSizeMore;
    public $maxFiles;
    public $path;

    // сделать композер пакет
    public function run()
    {
        $model = new DynamicModel([$this->attribute]);
        $model->addRule([$this->attribute], 'file', ['extensions' => $this->extensions,
            'maxSize' => $this->maxSizeOne,
            'maxFiles' => $this->maxFiles]);
        $fileSize = [];
        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstances($model, $this->attribute);
            foreach ($file as $i => $item) {
                array_push($fileSize, $file[$i]->size);
            }
            if ($model->validate() && $this->maxSizeMore >= array_sum($fileSize)) {
                if ($file) {
                    foreach ($file as $i => $item) {
                        $metaInfo = exif_read_data($file[$i]->tempName);
                        $meta = new Meta([
                            'file_path' => "/$this->path",
                            'file_date_time' => $metaInfo['FileDateTime'],
                            'file_size' => $metaInfo['FileSize'],
                            'file_type'=> $metaInfo['FileType'],
                            'mime_type' => $metaInfo['MimeType'],
                            'height' => $metaInfo['COMPUTED']['Height'],
                            'width' => $metaInfo['COMPUTED']['Width'],
                            'is_color' => $metaInfo['COMPUTED']['IsColor'],
                            'byte_order_motorola' => $metaInfo['COMPUTED']['ByteOrderMotorola'],
                            'copyright' => $metaInfo['COMPUTED']['Copyright'],
                            'image_description' => $metaInfo['ImageDescription'],
                            'artist' => $metaInfo['Artist'],
                            'exif_ifd_pointer' => $metaInfo['Exif_IFD_Pointer'],
                            'title' => $metaInfo['Title'],
                            'comments' => $metaInfo['Comments'],
                            'make' => $metaInfo['Make'],
                            'model' => $metaInfo['Model'],
                            'date_time_original' => $metaInfo['DateTimeOriginal'],
                            'date_time_digitized' => $metaInfo['DateTimeDigitized'],
                            'sub_sec_time_original' => $metaInfo['SubSecTimeOriginal'],
                            'sub_sec_time_digitized' => $metaInfo['SubSecTimeDigitized']]);
                        if ($meta->save()) {
                            $image = new $this->model([$this->attribute => $this->uploadFile($item), $this->metaAttribute => $meta->id]);
                            if ($image->save()) {
                                Yii::$app->session->setFlash('success', 'Загрузка прошла успешно');
                            }
                        }
                    }
                } else {
                    Yii::$app->session->setFlash('danger', 'Файл не найден');
                }
            } else {
                Yii::$app->session->setFlash('danger', 'Файлы не могу быть загружены на сервер');
            }
        }

        return $this->render('_template', [
            'model' => $model,
            'attribute' => $this->attribute
        ]);
    }

    public function uploadFile($file)
    {
        $filename = strtolower(md5(uniqid($file->baseName)) . '.' . $file->extension);
        if (isset($this->path)) {
            FileHelper::createDirectory(Yii::getAlias('@web') . 'uploads/' . $this->path);
            $file->saveAs(Yii::getAlias('@web') . 'uploads/' . $this->path . '/' . $filename);
        } else {
            FileHelper::createDirectory(Yii::getAlias('@web') . 'uploads');
            $file->saveAs(Yii::getAlias('@web') . 'uploads/' . $filename);
        }

        return $filename;
    }

}