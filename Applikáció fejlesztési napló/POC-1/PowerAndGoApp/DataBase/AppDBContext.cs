using Microsoft.EntityFrameworkCore;
using PowerAndGoApp.DataStore;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace PowerAndGoApp.DataBase
{
    public class AppDBContext : DbContext //Entity FrameWork alapért. adatbázis-kapcsolatát biztosítjuk.
    {
        // Táblák, kapcsolatok beállítása
        public DbSet<Auto> Autok { get; set; }
        public DbSet<Kategoria> Kategoriak { get; set; }
        public DbSet<Felszereltseg> Felszereltsegek { get; set; }
        public DbSet<Szemely> Szemelyek { get; set; }
        public DbSet<Felhasznalo> Felhasznalok { get; set; }
        public DbSet<Lezart_berles> Lezart_Berlesek { get; set; }


        protected override void OnConfiguring(DbContextOptionsBuilder optionsBuilder)
        {
            var adatbazisUtvonal = Path.Combine(FileSystem.AppDataDirectory, "PowerAndGoApp.db");
            optionsBuilder.UseSqlite($"Filename={adatbazisUtvonal}");
        }
        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            // Auto konfiguráció
            modelBuilder.Entity<Auto>()
                .HasKey(a => a.Rendszam);

            // Kategoria konfiguráció
            modelBuilder.Entity<Kategoria>()
                .HasKey(k => k.Rendszam);

            modelBuilder.Entity<Kategoria>()
                .HasOne(k => k.Auto)
                .WithOne(a => a.Kategoria)
                .HasForeignKey<Kategoria>(k => k.Rendszam);

            // Felszereltseg konfiguráció
            modelBuilder.Entity<Felszereltseg>()
                .HasKey(f => f.Rendszam);

            modelBuilder.Entity<Felszereltseg>()
                .HasOne(f => f.Auto)
                .WithOne(a => a.Felszereltseg)
                .HasForeignKey<Felszereltseg>(f => f.Rendszam);

            // Szemely és Felhasznalo kapcsolat
            modelBuilder.Entity<Felhasznalo>()
                .HasKey(f => f.ID);

            modelBuilder.Entity<Felhasznalo>()
                .HasOne(f => f.Szemely)
                .WithOne(s => s.Felhasznalo)
                .HasForeignKey<Felhasznalo>(f => f.ID);

            // Lezart_berles konfiguráció
            modelBuilder.Entity<Lezart_berles>()
                .HasKey(lb => lb.Berles_id);

            modelBuilder.Entity<Lezart_berles>()
                .HasOne(lb => lb.Auto)
                .WithMany(a => a.LezartBerlesek)
                .HasForeignKey(lb => lb.Rendszam);

            modelBuilder.Entity<Lezart_berles>()
                .HasOne(lb => lb.Felhasznalo)
                .WithMany(f => f.LezartBerlesek)
                .HasForeignKey(lb => lb.Felh_nev)
                .HasPrincipalKey(f => f.Felh_nev);
        }
    }
}