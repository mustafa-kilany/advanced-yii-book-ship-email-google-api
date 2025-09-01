<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $book common\models\Book */
/* @var $authors array */

$this->title = 'Create New Book';
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['book/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="book-create">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
                </div>
                
                <div class="card-body">
                    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Basic Information -->
                            <div class="form-group mb-3">
                                <?= $form->field($book, 'title')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <?= $form->field($book, 'isbn')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <?= $form->field($book, 'genre')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <?= $form->field($book, 'page_count')->textInput(['type' => 'number', 'class' => 'form-control']) ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <?= $form->field($book, 'published_date')->textInput(['type' => 'date', 'class' => 'form-control']) ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <?= $form->field($book, 'language')->dropDownList([
                                            'en' => 'English',
                                            'es' => 'Spanish',
                                            'fr' => 'French',
                                            'de' => 'German',
                                            'it' => 'Italian',
                                            'pt' => 'Portuguese',
                                            'ru' => 'Russian',
                                            'ja' => 'Japanese',
                                            'zh' => 'Chinese',
                                            'ar' => 'Arabic',
                                        ], ['class' => 'form-control']) ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <?= $form->field($book, 'status')->dropDownList([
                                            $book::STATUS_ACTIVE => 'Active',
                                            $book::STATUS_INACTIVE => 'Inactive',
                                        ], ['class' => 'form-control']) ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group mb-3">
                                <?= $form->field($book, 'description')->textarea(['rows' => 4, 'class' => 'form-control']) ?>
                            </div>
                            
                            <div class="form-group mb-3">
                                <?= $form->field($book, 'content')->textarea(['rows' => 8, 'class' => 'form-control']) ?>
                                <small class="form-text text-muted">
                                    Enter the main content or first chapter content here. You can add more chapters later.
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <!-- Author Selection -->
                            <div class="form-group mb-3">
                                <?= $form->field($book, 'author_id')->dropDownList(
                                    $authors,
                                    ['class' => 'form-control']
                                ) ?>
                            </div>
                            
                            <!-- Cover Image -->
                            <div class="form-group mb-3">
                                <label class="form-label">Cover Image</label>
                                <div class="input-group">
                                    <input type="file" name="cover_image" class="form-control" accept="image/*">
                                </div>
                                <small class="form-text text-muted">
                                    Upload a cover image (JPG, PNG, GIF). Recommended size: 300x400 pixels.
                                </small>
                            </div>
                            
                            <!-- Preview -->
                            <div class="cover-preview mt-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Cover Preview</h6>
                                    </div>
                                    <div class="card-body text-center">
                                        <div id="cover-preview-placeholder" class="cover-preview-placeholder">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                            <p class="text-muted mt-2">No image selected</p>
                                        </div>
                                        <img id="cover-preview-image" src="" alt="Cover Preview" class="cover-preview-image d-none">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mt-4">
                        <div class="d-flex justify-content-between">
                            <a href="<?= Url::to(['book/index']) ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Books
                            </a>
                            
                            <div>
                                <?= Html::submitButton('<i class="fas fa-save"></i> Save Book', ['class' => 'btn btn-primary']) ?>
                                <?= Html::a('<i class="fas fa-plus"></i> Save & Add Chapter', '#', [
                                    'class' => 'btn btn-success',
                                    'id' => 'save-and-add-chapter'
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.cover-preview-placeholder {
    width: 200px;
    height: 267px;
    background-color: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.cover-preview-image {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.btn {
    border-radius: 6px;
    font-weight: 500;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

.btn-success {
    background-color: #28a745;
    border-color: #28a745;
}

.btn-success:hover {
    background-color: #1e7e34;
    border-color: #1e7e34;
}

@media (max-width: 768px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .d-flex.justify-content-between .d-flex {
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.querySelector('input[name="cover_image"]');
    const previewPlaceholder = document.getElementById('cover-preview-placeholder');
    const previewImage = document.getElementById('cover-preview-image');
    
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.classList.remove('d-none');
                previewPlaceholder.classList.add('d-none');
            };
            reader.readAsDataURL(file);
        } else {
            previewImage.classList.add('d-none');
            previewPlaceholder.classList.remove('d-none');
        }
    });
    
    // Save and Add Chapter functionality
    document.getElementById('save-and-add-chapter').addEventListener('click', function(e) {
        e.preventDefault();
        
        // Add a hidden field to indicate we want to add a chapter after saving
        const hiddenField = document.createElement('input');
        hiddenField.type = 'hidden';
        hiddenField.name = 'add_chapter';
        hiddenField.value = '1';
        
        const form = document.querySelector('form');
        form.appendChild(hiddenField);
        
        // Submit the form
        form.submit();
    });
});
</script>
