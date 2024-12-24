import 'package:flutter_localization/flutter_localization.dart';


const List<MapLocale> LOCALES = [
  MapLocale("fr", LocaleData.FR),
  MapLocale("ar", LocaleData.Ar),

];

mixin LocaleData {
  static const String save = 'save';
  static const String title = 'title';
  static const String logout = 'logout';
  static const String savechanges = 'savechanges';
  static const String passwordactuel = 'passwordactuel';
  static const String newpass = 'new pass';
  static const String cpass = 'cpass';
  static const String fname = 'fname';
  static const String lname = 'lname';
  static const String email = 'email';
  static const String phone = 'phone';
  static const String adr = 'adr';
  static const String Profile = 'Profile';
  static const String changepassword = 'change password';
  static const String home = 'home';




  static const Map<String, dynamic> FR = {
    save:'enregistrer',
    title: 'Paramétres',
    logout: 'Déconnexion',
    savechanges:'Enregistrer les modifications',
    passwordactuel:'Mot de passe actuel',
    newpass:'Nouveau mot de passe',
    cpass:'Confirmer le nouveau mot de passe',
    fname:' Prénom',
    lname:'Nom',
    email:'Email',
    phone:'Téléphone',
    adr:'Adresse',
    Profile:'Profile',
    changepassword:'changer mot de passe',
    home:'page principale'




  };

  static const Map<String, dynamic> Ar = {
    save:'حفظ',
    title: 'الإعدادات',
    logout: 'تسجيل الخروج',
    savechanges: 'حفظ التغييرات',
    passwordactuel: 'كلمة المرور الحالية',
    newpass: 'كلمة المرور الجديدة',
    cpass: 'تأكيد كلمة المرور الجديدة',
    fname: 'الإسم الأول',
    lname: 'الإسم الأخير',
    email: 'البريد الإلكتروني',
    phone: 'الهاتف',
    adr: 'العنوان',
    Profile:'ملفك الشخصي',
    changepassword:'غير كلمة المرور',
    home:'الصفحة الرئيسية'

  };


}
