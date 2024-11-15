using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Navigation;
using System.Windows.Shapes;


namespace AutoBerlesRendszer.GUI
{

    public partial class MainWindow : Window
    {
        private AutoBerlesLogika logika;

        public MainWindow()
        {
            InitializeComponent();
            AutoTarolo autoTarolo = new AutoTarolo();
            KategoriaTarolo kategoriaTarolo = new KategoriaTarolo();
            FelhasznaloTarolo felhasznaloTarolo = new FelhasznaloTarolo();
            SzemelyTarolo szemelyTarolo = new SzemelyTarolo();
            BerlesTarolo berlesTarolo = new BerlesTarolo();

            autoTarolo.BeolvasAutoFajlbol("Autok.txt");
            kategoriaTarolo.BeolvasKategoriaFajlbol("Kategoriak.txt");
            felhasznaloTarolo.BeolvasFelhasznaloFajlbol("Felhasznalok.txt");
            szemelyTarolo.BeolvasSzemelyFajlbol("Szemely.txt");
            berlesTarolo.BeolvasBerlesFajlbol("Berlesek.txt");

            logika = new AutoBerlesLogika(autoTarolo, kategoriaTarolo, felhasznaloTarolo, szemelyTarolo, berlesTarolo);
        }

        private void Button_Click(object sender, RoutedEventArgs e)
        {
            string felhasznaloNev = textbox1.Text;
            try
            {
                int osszesVezetesiPerc = logika.GetFelhasznaloOsszesVezetesiPerce(felhasznaloNev);
                textblock.Text = $"A felhasználó összes vezetési perce: {osszesVezetesiPerc} perc";
            }
            catch (Exception ex)
            {
                textblock.Text = $"Hiba történt: {ex.Message}";
            }
        }

        private void Button_Click_1(object sender, RoutedEventArgs e)
        {
            string felhasznaloNev = textbox3.Text;
            string honapNev = textbox3.Text;

            try
            {
                var berlesek = logika.GetBerlesekFelhasznaloSzerint(felhasznaloNev);
                var berlesekHonapban = logika.GetBerlesekFelhasznaloSzerintHonapban(felhasznaloNev, honapNev);

                if (berlesek.Count == 0)
                {
                    textblock.Text = "Nincsenek bérlések ehhez a felhasználóhoz.";
                }
                else
                {
                    textblock.Text = "Összes bérlés:\n";
                    foreach (var berles in berlesek)
                    {
                        textblock.Text += $"Bérlés: {berles.BerlesKezd} - {berles.BerlesVeg}, Rendszám: {berles.Rendszam}\n";
                    }
                }

                if (berlesekHonapban.Count == 0)
                {
                    textblock.Text += "\nNincsenek bérlések ebben a hónapban ehhez a felhasználóhoz.";
                }
                else
                {
                    textblock.Text += "\nBérlések a megadott hónapban:\n";
                    foreach (var berles in berlesekHonapban)
                    {
                        textblock.Text += $"Bérlés: {berles.BerlesKezd} - {berles.BerlesVeg}, Vezetési percek: {(int)(berles.BerlesVeg - berles.BerlesKezd).TotalMinutes}\n";
                    }
                }
            }
            catch (Exception ex)
            {
                textblock.Text = $"Hiba történt: {ex.Message}";
            }
        }


        private void Button_Click_2(object sender, RoutedEventArgs e)
        {
        }
    }
}
