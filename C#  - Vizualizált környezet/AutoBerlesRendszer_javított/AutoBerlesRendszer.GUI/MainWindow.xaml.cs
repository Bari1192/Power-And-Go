using AutoBerlesRendszer.Logic;
using AutoBerlesRendszer.Repository;
using System;
using System.Collections.Generic;
using System.IO;
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

            autoTarolo.BeolvasAutoFajlbol(GetFilePath("Autok.txt"));
            kategoriaTarolo.BeolvasKategoriaFajlbol(GetFilePath("Kategoriak.txt"));
            felhasznaloTarolo.BeolvasFelhasznaloFajlbol(GetFilePath("Felhasznalok.txt"));
            szemelyTarolo.BeolvasSzemelyFajlbol(GetFilePath("Szemely.txt"));
            berlesTarolo.BeolvasBerlesFajlbol(GetFilePath("Berles.txt"));

            logika = new AutoBerlesLogika(autoTarolo, kategoriaTarolo, felhasznaloTarolo, szemelyTarolo, berlesTarolo);
        }

        // Mivel az istenért nem olvassa be a fájlokat, hiába adom meg existing item-ként reference-be.
        // Be is állítom a 'content' és a 'copy as newer opciót, de akkor is elszáll...
        private string GetFilePath(string fileName)
        {
            string directoryPath = AppDomain.CurrentDomain.BaseDirectory;
            string filePath = System.IO.Path.Combine(directoryPath, fileName);
            return filePath;
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
            string felhasznaloNev = textbox1.Text;
            var berlesek = logika.GetBerlesekFelhasznaloSzerint(felhasznaloNev);
        }


        private void Button_Click_2(object sender, RoutedEventArgs e)
        {
        }

        private void textbox1_belekattintas(object sender, RoutedEventArgs e)
        {
            string kezdetiszoveg = textbox1.Text;
            if (textbox1.Text == kezdetiszoveg)
            {
                textbox1.Text = "";
                textbox1.Opacity = 1;
            }
        }

        //private void textbox3(object sender, RoutedEventArgs e)
        //{

        //}

        private void textbox3_belekattintas(object sender, RoutedEventArgs e)
        {
            //textbox3.Opacity = 0.5;

            //string kezdetiszoveg = textbox3.Text;
            //if (textbox3.Text == kezdetiszoveg)
            //{
            //    textbox3.Text = "";
            //    textbox3.Opacity = 1;
            //}
        }
    }
}
