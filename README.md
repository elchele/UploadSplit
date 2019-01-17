# UploadSplit

Custom UploadStream class for creating date based sub-directory structure within Upload directory. 

This customization provides greater flexibility storing files attached to SugarCRM data. For example, Documents and Notes.
By default, the attached files are stored in the directory named "upload." This customization will utilize the date on which
the file was attached to create a directory structure similar to the following, where the file is then stored:

e.g. ./upload/2015/04/02/<SomeGUIDRepresentingTheAttachedFileFrom>
