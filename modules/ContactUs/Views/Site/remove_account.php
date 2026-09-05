<?=$this->extend('site_layout/template');?>
<?= $this->section('content') ?>
<header class="jumbotron bg-img-1">
    <div class="container">
        <h1> طلب حذف حساب من التطبيق "ع الطلب"</h1>
        <p>تابع الخطوات التالية لطلب حذف حسابك</p>
    </div>
</header><!-- jumbotron End -->
<article class="container">
    <ol class="breadcrumb" dir="<?= lang('dir'); ?>">
        <li class="active">
            قم بملأ بيانات الحساب المراد حذفه
        </li>
    </ol><!-- Breadcrumb End -->
    <!-- Main content -->
    <section class="content">
        <!-- general form elements -->
        <h2 class="page-header">الخطوات المطلوبة</h2>
        <ul>
            <li>قم بملأ بيانات الحساب المراد حذفه في النموذج أدناه</li>
            <li>أرسل النموذج</li>
            <li>سوف نقوم بمراجعة طلبك وحذف البيانات الخاصة بك بناءً على سياساتنا للبيانات</li>
        </ul>

        <h2 class="page-header">نوع البيانات التي سيتم حذفها</h2>
        <p>عندما تطلب حذف حسابك، سنحذف البيانات المرتبطة بحسابك مثل المعلومات الشخصية وتاريخ التطبيق. نحن نحتفظ ببعض
            البيانات لمدة 30 يومًا لأغراض الامتثال للقانون وحماية المستخدمين.</p>

        <h2 class="page-header">أرسل طلب حذف الحساب</h2>
        <div class="row">
            <section class="col-md-6">

                <?= $this->include('site_layout/site_msg'); ?>
                <?= form_open_multipart(); ?>
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>"/>
                <div class="form-group">
                        <input type="text" value="" name="contact_name"
                               class="form-control" placeholder="<?= lang("ContactUs.contact_name"); ?>">
                    </div>

                    <div class="form-group">
                        <input type="text" value="" name="contact_mobile"
                               class="form-control" placeholder="<?= lang("ContactUs.contact_mobile"); ?>">
                    </div>
                    <div class="form-group">
                        <input type="text" value="" name="contact_subject"
                               class="form-control" placeholder="<?= lang("ContactUs.contact_subject"); ?>">
                    </div>

                    <div class="form-group">
                        <textarea class="form-control" name="contact_message" rows="4"
                                  placeholder="<?= lang("ContactUs.contact_message"); ?>"></textarea>
                    </div>


                    <button type="reset" class="btn btn-default"><?= lang('ContactUs.reset'); ?></button>
                    <button type="submit" class="btn btn-primary"><?= lang('ContactUs.save_msg'); ?></button>
                </form>
            </section><!-- Contact Us Form End -->
        </div>
    </section>

</article>
<?= $this->endSection() ?>
