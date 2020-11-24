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

    public function run()
    {
        $model = new DynamicModel([$this->attribute]);
        $model->addRule([$this->attribute], 'file', ['extensions' => $this->extensions,
            'maxSize' => $this->maxSizeOne,
            'maxFiles' => $this->maxFiles]);

        $this->saveIntoDataBase($model);

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

    public function saveIntoDataBase($model)
    {
        $fileSize = [];
        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstances($model, $this->attribute);
            if (isset($this->maxSizeMore)) {
                foreach ($file as $i => $item) {
                    array_push($fileSize, $file[$i]->size);
                }
            }
            if ($model->validate() && $this->maxSizeMore >= array_sum($fileSize)) {
                if ($file) {
                    foreach ($file as $i => $item) {
                        $metaInfo = exif_read_data($file[$i]->tempName);
                        $meta = new Meta([
                            'file_path' => "/$this->path",
                            'file_date_time' => array_key_exists('FileDateTime', $metaInfo) ? $metaInfo['FileDateTime'] : null,
                            'file_size' => array_key_exists('FileSize', $metaInfo) ? $metaInfo['FileSize'] : null,
                            'file_type'=> array_key_exists('FileType', $metaInfo) ? $metaInfo['FileType'] : null,
                            'mime_type' => array_key_exists('MimeType', $metaInfo) ? $metaInfo['MimeType'] : null,
                            'height' => array_key_exists('Height', $metaInfo['COMPUTED']) ? $metaInfo['COMPUTED']['Height'] : null,
                            'width' => array_key_exists('Width', $metaInfo['COMPUTED']) ? $metaInfo['COMPUTED']['Width'] : null,
                            'is_color' => array_key_exists('IsColor', $metaInfo['COMPUTED']) ? $metaInfo['COMPUTED']['IsColor'] : null,
                            'byte_order_motorola' => array_key_exists('ByteOrderMotorola', $metaInfo['COMPUTED']) ? $metaInfo['COMPUTED']['ByteOrderMotorola'] : null,
                            'copyright' => array_key_exists('Copyright', $metaInfo['COMPUTED']) ? $metaInfo['COMPUTED']['Copyright'] : null,
                            'image_description' => array_key_exists('ImageDescription', $metaInfo) ? $metaInfo['ImageDescription'] : null,
                            'artist' => array_key_exists('Artist', $metaInfo) ? $metaInfo['Artist'] : null,
                            'exif_ifd_pointer' => array_key_exists('Exif_IFD_Pointer', $metaInfo) ? $metaInfo['Exif_IFD_Pointer'] : null,
                            'title' => array_key_exists('Title', $metaInfo) ? $metaInfo['Title'] : null,
                            'comments' => array_key_exists('Comments', $metaInfo) ? $metaInfo['Comments'] : null,
                            'make' => array_key_exists('Make', $metaInfo) ? $metaInfo['Make'] : null,
                            'model' => array_key_exists('Model', $metaInfo) ? $metaInfo['Model'] : null,
                            'date_time_original' => array_key_exists('DateTimeOriginal', $metaInfo) ? $metaInfo['DateTimeOriginal'] : null,
                            'date_time_digitized' => array_key_exists('DateTimeDigitized', $metaInfo) ? $metaInfo['DateTimeDigitized'] : null,
                            'sub_sec_time_original' => array_key_exists('SubSecTimeOriginal', $metaInfo) ? $metaInfo['SubSecTimeOriginal'] : null,
                            'sub_sec_time_digitized' => array_key_exists('SubSecTimeDigitized', $metaInfo) ? $metaInfo['SubSecTimeDigitized'] : null]);
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
                Yii::$app->session->setFlash('danger', 'Файлы не могу быть загружены на сервер, так как их размер превышает максимальный');
            }
        }
    }
}