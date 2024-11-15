using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AutoBerlesRendszer.Repository
{
    public interface IRepository<T> where T : class
    {
        void Hozzaadas(T entity);
        void Torles(int id);
        void Frissites(T entity);
        T AzonositoAlapjan(int id);
        List<T> Osszes();
    }
}