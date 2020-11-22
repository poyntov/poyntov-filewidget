<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "meta".
 *
 * @property int $id
 * @property string|null $file_path
 * @property int|null $file_date_time
 * @property int|null $file_size
 * @property int|null $file_type
 * @property string|null $mime_type
 * @property int|null $height
 * @property int|null $width
 * @property int|null $is_color
 * @property int|null $byte_order_motorola
 * @property string|null $copyright
 * @property string|null $image_description
 * @property string|null $artist
 * @property int|null $exif_ifd_pointer
 * @property string|null $title
 * @property string|null $comments
 * @property string|null $make
 * @property string|null $model
 * @property string|null $date_time_original
 * @property string|null $date_time_digitized
 * @property int|null $sub_sec_time_original
 * @property int|null $sub_sec_time_digitized
 *
 * @property File[] $files
 */
class Meta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'meta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file_date_time', 'file_size', 'file_type', 'height', 'width', 'is_color', 'byte_order_motorola', 'exif_ifd_pointer', 'sub_sec_time_original', 'sub_sec_time_digitized'], 'integer'],
            [['date_time_original', 'date_time_digitized'], 'safe'],
            [['file_path', 'mime_type', 'copyright', 'image_description', 'artist', 'title', 'comments', 'make', 'model'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file_path' => 'File Path',
            'file_date_time' => 'File Date Time',
            'file_size' => 'File Size',
            'file_type' => 'File Type',
            'mime_type' => 'Mime Type',
            'height' => 'Height',
            'width' => 'Width',
            'is_color' => 'Is Color',
            'byte_order_motorola' => 'Byte Order Motorola',
            'copyright' => 'Copyright',
            'image_description' => 'Image Description',
            'artist' => 'Artist',
            'exif_ifd_pointer' => 'Exif Ifd Pointer',
            'title' => 'Title',
            'comments' => 'Comments',
            'make' => 'Make',
            'model' => 'Model',
            'date_time_original' => 'Date Time Original',
            'date_time_digitized' => 'Date Time Digitized',
            'sub_sec_time_original' => 'Sub Sec Time Original',
            'sub_sec_time_digitized' => 'Sub Sec Time Digitized',
        ];
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::className(), ['id_meta' => 'id']);
    }
}
